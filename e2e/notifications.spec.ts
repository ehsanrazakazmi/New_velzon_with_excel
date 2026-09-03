import { test, expect, Page } from '@playwright/test';

/**
 * Admin bell notifications. Serial because each step depends on the bell state
 * the previous step left behind.
 */
test.describe.serial('Admin notifications', () => {
  test.use({ viewport: { width: 1920, height: 1080 } });

  const serial = `SN-NOTIF-${Date.now()}`;

  /**
   * The badge holds a visually-hidden "unread notifications" span for screen
   * readers, so its textContent is e.g. "3unread notifications". Read just the
   * leading number instead of asserting on raw text.
   */
  async function badgeCount(page: Page): Promise<number> {
    const badge = page.locator('#notification-badge');
    if ((await badge.count()) === 0) return 0;
    const raw = await badge.evaluate((el) => el.childNodes[0]?.textContent?.trim() ?? '');
    return Number(raw);
  }

  /** Open the bell dropdown and wait for it to actually be on screen. */
  async function openBell(page: Page) {
    await page.locator('#page-header-notifications-dropdown').click();
    await expect(page.locator('#notification-list')).toBeVisible();
  }

  /** Empty the bell so a test starts from a known baseline. */
  async function clearBell(page: Page) {
    await page.goto('');
    const badge = page.locator('#notification-badge');
    while (await badge.count()) {
      await openBell(page);
      await page.locator('#mark-all-read').click();
      await page.waitForLoadState('load');
    }
  }

  test('the dummy template notifications are gone', async ({ page }) => {
    await page.goto('');
    const body = page.locator('body');
    await expect(body).not.toContainText('Angela Bernier');
    await expect(body).not.toContainText('Maureen Gibson');
  });

  test('bell is empty once everything is read', async ({ page }) => {
    await clearBell(page);
    await openBell(page);
    await expect(page.locator('#notification-empty')).toBeVisible();
    await expect.poll(() => badgeCount(page)).toBe(0);
  });

  test('adding a laptop raises an admin notification', async ({ page }) => {
    await clearBell(page);

    await page.goto('laptop/list');
    await page.locator('#create-btn').click();
    const modal = page.locator('#showModal');
    await expect(modal).toBeVisible();
    await modal.locator('#brand').fill('Apple');
    await modal.locator('#model').fill('MacBook Pro 14');
    await modal.locator('#serial_number').fill(serial);
    await modal.locator('#price').fill('2400');
    await modal.getByRole('button', { name: 'Add Laptop' }).click();
    await expect(page.locator('.alert-success')).toBeVisible();

    await expect.poll(() => badgeCount(page)).toBe(1);

    await openBell(page);
    const item = page.locator('.notification-item').first();
    await expect(item).toContainText('Laptop added');
    await expect(item).toContainText(serial);
    await expect(item).toContainText('Super Admin'); // the actor
  });

  test('editing and deleting the laptop each add a notification', async ({ page }) => {
    await page.goto('laptop/list');
    await page.locator('tr', { hasText: serial }).locator('a[href*="laptop/edit"]').click();
    await page.locator('#processor').fill('Apple M3 Pro');
    await page.getByRole('button', { name: 'Update' }).click();
    await expect.poll(() => badgeCount(page)).toBe(2);

    await page.locator('tr', { hasText: serial }).locator('button.remove-item-btn').click();
    await page.locator('.modal.show').getByRole('link', { name: 'Delete it!' }).click();
    await expect.poll(() => badgeCount(page)).toBe(3);

    await openBell(page);
    await expect(page.locator('#notification-list')).toContainText('Laptop removed');
    await expect(page.locator('#notification-list')).toContainText('Laptop updated');
  });

  test('mark all as read clears the bell', async ({ page }) => {
    await page.goto('');
    await expect.poll(() => badgeCount(page)).toBe(3);

    await openBell(page);
    await page.locator('#mark-all-read').click();

    await expect.poll(() => badgeCount(page)).toBe(0);
    await openBell(page);
    await expect(page.locator('#notification-empty')).toBeVisible();
  });
});
