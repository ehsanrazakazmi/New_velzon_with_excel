import { test, expect } from '@playwright/test';

/**
 * Guest specs must NOT reuse the cached login, so the stored state is dropped
 * for this file only.
 */
test.use({ storageState: { cookies: [], origins: [] } });

const PROTECTED_PATHS = [
  'profile/view',
  'profile/edit/page',
  'product/list',
  'laptop/list',
  'user/list',
  'subscriptions/all',
];

for (const path of PROTECTED_PATHS) {
  test(`guest visiting "${path}" is redirected to login`, async ({ page }) => {
    await page.goto(path);
    await expect(page).toHaveURL(/login/);
    await expect(page.locator('input[name="password"]')).toBeVisible();
  });
}

test('login page renders its form', async ({ page }) => {
  await page.goto('login');
  await expect(page.locator('input[name="email"]')).toBeVisible();
  await expect(page.getByRole('button', { name: 'Sign In' })).toBeVisible();
});
