import { test, expect } from '@playwright/test';

test('dashboard loads for an authenticated user', async ({ page }) => {
  await page.goto('');
  await expect(page).toHaveTitle(/Dashboards/i);
});

test('topbar subscription widget renders without error', async ({ page }) => {
  // Regression guard: this widget queries Subscription and eager-loads the
  // plan relation. A broken model binding here 500s the whole layout.
  const response = await page.goto('');
  expect(response?.status()).toBe(200);
  await expect(page.locator('body')).not.toContainText('Call to undefined relationship');
});

test('subscriptions page resolves the plan relation', async ({ page }) => {
  // Exercises App\Models\Subscription::plan() end to end: the blade renders
  // $subscription->plan->name, so a name in the table proves the relation works.
  await page.goto('subscriptions/all');
  await expect(page.getByRole('table')).toBeVisible();
  await expect(page.getByRole('table')).toContainText(/Basic|professional|enterprise/i);
});

test('product list is reachable', async ({ page }) => {
  await page.goto('product/list');
  await expect(page).toHaveURL(/product\/list/);
  await expect(page.locator('body')).not.toContainText('403');
});

test('plans page lists the seeded plans', async ({ page }) => {
  await page.goto('plans');
  const body = page.locator('body');
  await expect(body).toContainText(/Basic/i);
  await expect(body).toContainText(/enterprise/i);
});

test('sidebar logo returns to the dashboard', async ({ page }) => {
  // The Velzon template shipped href="index", which fell through to the {any}
  // catch-all and 404'd. It must land on the dashboard from any depth.
  await page.goto('product/list');
  // Velzon renders both a dark and a light logo anchor; only one is visible.
  await page.locator('.navbar-brand-box a.logo:visible').first().click();
  await expect(page).toHaveTitle(/Dashboards/i);
});

test('logo on the login page does not 404 for guests', async ({ page }) => {
  await page.context().clearCookies();
  await page.goto('login');
  const response = await page.request.get(
    (await page.locator('a.auth-logo').first().getAttribute('href')) as string
  );
  expect(response.status()).toBeLessThan(400);
});
