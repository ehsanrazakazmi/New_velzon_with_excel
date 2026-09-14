import { defineConfig, devices } from '@playwright/test';

/**
 * The app is served by XAMPP's Apache out of a subdirectory, so baseURL MUST
 * keep its trailing slash and specs MUST use relative paths without a leading
 * slash (`page.goto('login')`, not `page.goto('/login')`). A leading slash
 * would resolve against the host root and escape the app entirely.
 */
export const APP_URL =
  process.env.APP_URL ?? 'http://localhost/New_velzon_with_excel/public/';

/** Seeded Super Admin from database/seeders/CreateUserSeeder.php */
export const CREDENTIALS = {
  email: process.env.E2E_EMAIL ?? '123@gmail.com',
  password: process.env.E2E_PASSWORD ?? '123456',
};

/** Where the logged-in browser state is cached between specs. */
export const STORAGE_STATE = 'playwright/.auth/user.json';

export default defineConfig({
  testDir: './e2e',

  /* Mints a fresh single-use onboarding link before the suite. */
  globalSetup: './e2e/global-setup.ts',

  /* These specs share one database and one admin account - laptop CRUD raises
     notifications that other specs assert on - so they must not run in
     parallel. Single worker also avoids the intermittent .env read failures
     Apache/Windows shows under concurrent requests. */
  fullyParallel: false,
  workers: 1,

  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,

  /* `list` prints results to the terminal so Claude can read them directly;
     the HTML report is written but never auto-opened. */
  reporter: [['list'], ['html', { open: 'never' }]],

  use: {
    baseURL: APP_URL,
    /* Artifacts on failure - these are what make a failure diagnosable. */
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    trace: 'on-first-retry',
  },

  projects: [
    /* Logs in once, saves cookies to STORAGE_STATE. Everything else reuses it. */
    { name: 'setup', testMatch: /.*\.setup\.ts/ },

    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'], storageState: STORAGE_STATE },
      dependencies: ['setup'],
    },

    /* Cross-browser runs. Enable when you want them - each one triples runtime.
    {
      name: 'firefox',
      use: { ...devices['Desktop Firefox'], storageState: STORAGE_STATE },
      dependencies: ['setup'],
    },
    {
      name: 'webkit',
      use: { ...devices['Desktop Safari'], storageState: STORAGE_STATE },
      dependencies: ['setup'],
    },
    */
  ],

  /* No webServer block: XAMPP's Apache already serves the app continuously. */
});
