import { test, expect } from '@playwright/test';

/**
 * Regressions around editing a user.
 *
 * Both bugs these cover came from the same mistake: the route carries an
 * encrypted id, and update() used that encrypted string directly in queries,
 * which silently matched nothing.
 */
test.describe.serial('User Management', () => {
  test.use({ viewport: { width: 1920, height: 1080 } });

  const email = `edit-check-${Date.now()}@example.test`;
  const movedEmail = `moved-check-${Date.now()}@example.test`;
  const name = 'Edit Check';

  // Reassigned by the email-change test - every later lookup must follow it.
  let current = email;

  /** The row for our user in the list table. */
  const row = (page: import('@playwright/test').Page) =>
    page.locator('tr', { hasText: current });

  test('create a user to work with', async ({ page }) => {
    await page.goto('user/list');
    await page.locator('#create-btn').click();

    const modal = page.locator('#showModal');
    await expect(modal).toBeVisible();
    await modal.locator('input[name="name"]').fill(name);
    await modal.locator('input[name="email"]').fill(email);
    await modal.locator('input[name="password"]').fill('TempPass123');
    await modal.locator('input[name="confirm-password"]').fill('TempPass123');
    await modal.locator('select[name="roles[]"]').selectOption('User');
    await modal.getByRole('button', { name: 'Add User' }).click();

    await expect(page).toHaveURL(/user\/list/);
    await expect(row(page)).toContainText('User');

    // Created but unproven: the address has not been shown to work yet.
    await expect(row(page).locator('.user-status')).toContainText('Pending activation');
  });

  test('saving with an unchanged email succeeds', async ({ page }) => {
    // Regression: the unique rule was given the encrypted id, so it could never
    // ignore this user's own row - their existing email counted as a duplicate
    // and every save failed, making it impossible to rename or re-role anyone.
    await page.goto('user/list');
    await row(page).locator('a[href*="user/edit"]').click();

    await page.locator('input[name="name"]').fill('Edit Check Renamed');
    await page.getByRole('button', { name: 'Update' }).click();

    await expect(page).toHaveURL(/user\/list/);
    await expect(page.locator('.alert-success')).toContainText('updated successfully');
    await expect(row(page)).toContainText('Edit Check Renamed');
  });

  test('changing the role replaces it rather than adding to it', async ({ page }) => {
    // Regression: roles used to accumulate. The list renders roles[0], so if the
    // old role were still attached this cell would still read "User".
    await page.goto('user/list');
    await row(page).locator('a[href*="user/edit"]').click();

    await page.locator('select[name="roles[]"]').selectOption('Mini-Admin');
    await page.getByRole('button', { name: 'Update' }).click();

    await expect(page.locator('.alert-success')).toBeVisible();
    await expect(row(page)).toContainText('Mini-Admin');
    await expect(row(page)).not.toContainText('User');
  });

  test('a user can be demoted', async ({ page }) => {
    // The case that was outright impossible before: dropping someone to a
    // lesser role left the higher one silently attached.
    await page.goto('user/list');
    await row(page).locator('a[href*="user/edit"]').click();

    await page.locator('select[name="roles[]"]').selectOption('User');
    await page.getByRole('button', { name: 'Update' }).click();

    await expect(page.locator('.alert-success')).toBeVisible();
    await expect(row(page)).toContainText('User');
    await expect(row(page)).not.toContainText('Mini-Admin');
  });

  test('the role edit form pre-selects the permissions the role holds', async ({ page }) => {
    await page.goto('roles/list');
    await page.locator('tr', { hasText: 'Mini-Admin' }).locator('a[href*="roles/edit"]').click();

    const selected = page.locator('select[name="permission[]"] option[selected]');
    await expect(selected.first()).toBeAttached();
    expect(await selected.count()).toBeGreaterThan(0);
  });

  test('the list shows every user, not just the first five', async ({ page }) => {
    // Regression: index() used paginate(5) while the view never rendered
    // Laravel's pager, so any user past the fifth was invisible AND unreachable
    // - DataTables only pages the rows it was handed.
    await page.goto('user/list');

    const rendered = await page.locator('table tbody tr').count();
    const reported = await page.locator('#example_info, .dataTables_info').first().innerText();

    // "Showing 1 to N of TOTAL entries" - the total must match the rows we got.
    const total = Number((reported.match(/of\s+(\d+)\s+entries/) || [])[1]);
    expect(total).toBeGreaterThan(0);
    expect(rendered).toBe(total);

    // And with the account this spec created, we are past the old cap of five.
    expect(total).toBeGreaterThan(5);
  });

  test('an account that finished onboarding reads as active', async ({ page }) => {
    // The seeded Super Admin has set a password, so it must not be flagged
    // pending - otherwise the badge would just say Pending for everyone.
    await page.goto('user/list');
    const admin = page.locator('tr', { hasText: '123@gmail.com' });
    await expect(admin.locator('.user-status')).toContainText('Active');
  });

  test('changing the email re-opens activation and mails the new address', async ({ page }) => {
    // A new address is unproven, so the account drops back to pending and is
    // unreachable by password until someone opens the link sent there.
    await page.goto('user/list');
    await row(page).locator('a[href*="user/edit"]').click();

    await page.locator('input[name="email"]').fill(movedEmail);
    await page.getByRole('button', { name: 'Update' }).click();
    current = movedEmail;

    await expect(page).toHaveURL(/user\/list/);
    await expect(page.locator('.alert-success')).toContainText('confirmation link was sent');
    await expect(page.locator('.alert-success')).toContainText(movedEmail);

    await expect(row(page)).toHaveCount(1);
    await expect(row(page).locator('.user-status')).toContainText('Pending activation');

    // And the admin can re-send from the list while it stays pending.
    await expect(row(page).locator('a[href*="resend-welcome"]')).toBeVisible();

    // The old address is gone entirely.
    await expect(page.locator('tr', { hasText: email })).toHaveCount(0);
  });

  test('a pending account cannot sign in with its password', async ({ page, context }) => {
    // The proof that "pending" is a real gate and not just a badge.
    await context.clearCookies();
    await page.goto('login');
    await page.locator('input[name="email"]').fill(movedEmail);
    await page.locator('input[name="password"]').fill('TempPass123');
    await page.getByRole('button', { name: 'Sign In' }).click();

    await expect(page).toHaveURL(/login/);
    await expect(page.locator('#activation-notice')).toContainText('has not been activated');
  });
  test('remove the test user', async ({ page }) => {
    await page.goto('user/list');
    await row(page).locator('button.remove-item-btn').click();
    await page.locator('.modal.show').getByRole('link', { name: 'Delete it!' }).click();

    await expect(row(page)).toHaveCount(0);
  });
});
