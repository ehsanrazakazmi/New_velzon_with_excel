import { test, expect } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

/**
 * The onboarding magic link from the welcome email.
 *
 * The fixture is produced by a small tinker script (see README of this folder)
 * because the signed URL can only be minted server-side - the signature covers
 * the whole URL including the expiry.
 */
const FIXTURE = path.join(__dirname, '.fixtures', 'welcome.json');

test.describe.serial('Welcome email sign-in link', () => {
  // The link signs in as the new user, so it must not reuse the admin session.
  test.use({ storageState: { cookies: [], origins: [] } });

  const fixture = fs.existsSync(FIXTURE)
    ? JSON.parse(fs.readFileSync(FIXTURE, 'utf8'))
    : null;

  test.skip(!fixture, 'no welcome fixture generated');

  // One test, because Playwright gives each test a fresh browser context - the
  // session established by the link would not survive into a separate test.
  test('link signs in, pins to password screen, then releases the account', async ({ page }) => {
    await page.goto(fixture.url);

    // 1. Landed signed in, on the password screen, as the new user.
    await expect(page).toHaveURL(/set-password/);
    await expect(page.getByRole('heading', { name: 'Choose your own password' })).toBeVisible();
    await expect(page.locator('body')).toContainText(fixture.email);

    // 2. Cannot wander off until a password is chosen.
    await page.goto('laptop/list');
    await expect(page).toHaveURL(/set-password/);
    await page.goto('product/list');
    await expect(page).toHaveURL(/set-password/);

    // 3. Setting one releases the pin.
    await page.locator('#new_password').fill('BrandNewPass123');
    await page.locator('#new_password_confirmation').fill('BrandNewPass123');
    await page.locator('#save-password').click();
    await expect(page).toHaveTitle(/Dashboards/i);

    await page.goto('product/list');
    await expect(page).toHaveURL(/product\/list/);
  });

  test('password login is refused until the link is used, and offers a resend', async ({ page }) => {
    // The `pending` fixture account is never activated. Its password is correct
    // (see e2e/fixtures/make-welcome.php) and must still be refused.
    await page.goto('login');
    await page.locator('input[name="email"]').fill(fixture.pending.email);
    await page.locator('input[name="password"]').fill(fixture.password);
    await page.getByRole('button', { name: 'Sign In' }).click();

    await expect(page).toHaveURL(/login/);
    await expect(page.locator('#activation-notice')).toBeVisible();
    await expect(page.locator('#activation-notice')).toContainText('has not been activated');
    await expect(page.locator('#resend-welcome')).toBeVisible();

    // And the app really is out of reach.
    await page.goto('laptop/list');
    await expect(page).toHaveURL(/login/);
  });

  test('resend reports back without revealing whether the account exists', async ({ page }) => {
    await page.goto('login');
    await page.locator('input[name="email"]').fill(fixture.pending.email);
    await page.locator('input[name="password"]').fill(fixture.password);
    await page.getByRole('button', { name: 'Sign In' }).click();
    await page.locator('#resend-welcome').click();

    await expect(page).toHaveURL(/login/);
    await expect(page.locator('.alert-success')).toContainText('awaiting activation');
  });

  test('the link is single use', async ({ page, context }) => {
    await context.clearCookies();
    await page.goto(fixture.url);

    // Bounced to login with an explanation rather than silently re-authenticating.
    await expect(page).toHaveURL(/login/);
    await expect(page.locator('.alert-warning')).toContainText('already been used');
  });
});

/**
 * Enrolment is only finished once the person has replaced the password, and
 * that is the moment administrators are told about. Deliberately a separate
 * describe: it needs the cached admin session, not the new user's.
 */
test.describe('Enrolment reaches the admin bell', () => {
  test.use({ viewport: { width: 1920, height: 1080 } });

  const fixture = fs.existsSync(FIXTURE)
    ? JSON.parse(fs.readFileSync(FIXTURE, 'utf8'))
    : null;

  test.skip(!fixture, 'no welcome fixture generated');

  test('setting the password notifies administrators', async ({ page }) => {
    await page.goto('');
    await page.locator('#page-header-notifications-dropdown').click();

    const list = page.locator('#notification-list');
    await expect(list).toBeVisible();
    await expect(list).toContainText('Account activated');
    await expect(list).toContainText(fixture.email);
  });
});
