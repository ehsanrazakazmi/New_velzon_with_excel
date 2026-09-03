import { test, expect } from '@playwright/test';

/**
 * Full CRUD walkthrough for Laptop Management, driven through the real UI.
 * Serial because each step depends on the record the previous step left behind.
 */
test.describe.serial('Laptop Management CRUD', () => {
  // The laptops table has 12 columns; at the default 1280px the DataTables
  // responsive plugin collapses the Actions column to display:none, so the
  // edit/delete controls are unclickable. Admin tables like this are used on
  // wide screens - test at a width where the whole table fits.
  test.use({ viewport: { width: 1920, height: 1080 } });

  // Unique per run so re-runs never collide with the unique serial_number index.
  const serial = `SN-E2E-${Date.now()}`;
  const brand = 'Dell';
  const model = 'Latitude 7440';

  test('sidebar exposes the Laptop Management menu', async ({ page }) => {
    await page.goto('laptop/list');
    // The sidebar collapses to icons at this viewport, so assert the menu entry
    // is rendered (proves the @can('Laptop list') gate passed) rather than visible.
    await expect(page.locator('#navbar-nav a[href$="laptop/list"]')).toHaveCount(1);
    await expect(page.locator('#navbar-nav a[href="#sidebarLaptops"]')).toHaveCount(1);
  });

  test('list page renders', async ({ page }) => {
    await page.goto('laptop/list');
    await expect(page.getByRole('heading', { name: 'Laptops' })).toBeVisible();
    await expect(page.getByRole('table')).toBeVisible();
  });

  test('status dropdown renders at full width', async ({ page }) => {
    // Regression: id="status" collided with Velzon's preloader rule
    //   #status{width:40px;height:40px;position:absolute;...}
    // which yanked the select out of flow and shrank it to ~60px, so the field
    // looked missing under its label. Renamed to #laptop_status.
    await page.goto('laptop/list');
    await page.locator('#create-btn').click();
    await expect(page.locator('#showModal')).toBeVisible();

    const status = page.locator('#laptop_status');
    await expect(status).toBeVisible();
    await expect(status).toHaveJSProperty('name', 'status'); // server contract unchanged

    const box = await status.boundingBox();
    const currency = await page.locator('#currency').boundingBox();
    expect(box!.width).toBeGreaterThan(300);
    expect(Math.round(box!.width)).toBe(Math.round(currency!.width));
    await expect(status).toHaveCSS('position', 'static');
  });

  test('create a laptop', async ({ page }) => {
    await page.goto('laptop/list');

    await page.locator('#create-btn').click();
    const modal = page.locator('#showModal');
    await expect(modal).toBeVisible();

    await modal.locator('#brand').fill(brand);
    await modal.locator('#model').fill(model);
    await modal.locator('#serial_number').fill(serial);
    await modal.locator('#processor').fill('Intel Core i7-1355U');
    await modal.locator('#ram_gb').fill('16');
    await modal.locator('#storage_gb').fill('512');
    await modal.locator('#price').fill('1499.99');
    await modal.locator('#currency').selectOption('USD');
    await modal.locator('#purchase_date').fill('2026-01-15');
    // Status deliberately left untouched - it is optional and must default.

    await modal.getByRole('button', { name: 'Add Laptop' }).click();

    await expect(page).toHaveURL(/laptop\/list/);
    await expect(page.locator('.alert-success')).toContainText('Laptop created successfully');

    const row = page.locator('tr', { hasText: serial });
    await expect(row).toBeVisible();
    await expect(row).toContainText(brand);
    await expect(row).toContainText('16 GB');
    await expect(row).toContainText('Available');
    await expect(row).toContainText('Super Admin');
  });

  test('duplicate serial number is rejected', async ({ page }) => {
    await page.goto('laptop/list');

    await page.locator('#create-btn').click();
    const modal = page.locator('#showModal');
    await modal.locator('#brand').fill('HP');
    await modal.locator('#model').fill('EliteBook');
    await modal.locator('#serial_number').fill(serial); // already taken
    await modal.locator('#price').fill('900');
    await modal.getByRole('button', { name: 'Add Laptop' }).click();

    await expect(page.locator('.alert-danger')).toContainText('serial number has already been taken');
  });

  test('edit the laptop', async ({ page }) => {
    await page.goto('laptop/list');

    const row = page.locator('tr', { hasText: serial });
    await row.locator('a[href*="laptop/edit"]').click();

    // Breadcrumb and card header both render an h4 with this text, so scope it.
    await expect(page.locator('h4.card-title', { hasText: 'Edit Laptop' })).toBeVisible();
    await expect(page.locator('#serial_number')).toHaveValue(serial);

    await page.locator('#ram_gb').fill('32');
    await page.locator('#laptop_status').selectOption('repair');
    await page.getByRole('button', { name: 'Update' }).click();

    await expect(page).toHaveURL(/laptop\/list/);
    await expect(page.locator('.alert-success')).toContainText('Laptop updated successfully');

    const updated = page.locator('tr', { hasText: serial });
    await expect(updated).toContainText('32 GB');
    await expect(updated).toContainText('Repair');
  });

  test('edit form pre-selects the stored status and currency', async ({ page }) => {
    // Regression: @selected is Laravel 9+, so on 8.x it rendered as literal text
    // and the dropdowns always fell back to the first option - meaning any edit
    // silently reset status to "available".
    await page.goto('laptop/list');
    await page.locator('tr', { hasText: serial }).locator('a[href*="laptop/edit"]').click();

    await expect(page.locator('#laptop_status')).toHaveValue('repair');
    await expect(page.locator('#currency')).toHaveValue('USD');
  });

  test('status is optional and blank edits leave it alone', async ({ page }) => {
    await page.goto('laptop/list');
    await page.locator('tr', { hasText: serial }).locator('a[href*="laptop/edit"]').click();

    // Currently 'repair' from the previous test; choose the blank option and save.
    await expect(page.locator('#laptop_status')).toHaveValue('repair');
    await page.locator('#laptop_status').selectOption('');
    await page.locator('#processor').fill('Intel Core i5-1345U');
    await page.getByRole('button', { name: 'Update' }).click();

    await expect(page.locator('.alert-success')).toContainText('updated successfully');

    const row = page.locator('tr', { hasText: serial });
    await expect(row).toContainText('Intel Core i5-1345U'); // the edit applied
    await expect(row).toContainText('Repair');              // status survived untouched
  });

  test('delete the laptop', async ({ page }) => {
    await page.goto('laptop/list');

    const row = page.locator('tr', { hasText: serial });
    await row.locator('button.remove-item-btn').click();

    const confirm = page.locator('.modal.show').getByRole('link', { name: 'Delete it!' });
    await expect(confirm).toBeVisible();
    await confirm.click();

    await expect(page.locator('.alert-success')).toContainText('Laptop deleted successfully');
    await expect(page.locator('tr', { hasText: serial })).toHaveCount(0);
  });
});
