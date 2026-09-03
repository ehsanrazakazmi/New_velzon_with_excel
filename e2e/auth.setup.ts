import { test as setup, expect } from '@playwright/test';
import { CREDENTIALS, STORAGE_STATE } from '../playwright.config';

/**
 * Runs once before every other spec. Logs in through the real form so the
 * session cookie is genuine, then caches it. Individual specs therefore start
 * already authenticated instead of logging in over and over.
 */
setup('authenticate', async ({ page }) => {
  await page.goto('login');

  await page.locator('input[name="email"]').fill(CREDENTIALS.email);
  await page.locator('input[name="password"]').fill(CREDENTIALS.password);
  await page.getByRole('button', { name: 'Sign In' }).click();

  // Landing on the dashboard is the proof that login actually succeeded.
  await expect(page).toHaveTitle(/Dashboards/i);

  await page.context().storageState({ path: STORAGE_STATE });
});
