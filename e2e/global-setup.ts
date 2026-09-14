import { execFileSync } from 'child_process';
import * as path from 'path';
import { APP_URL } from '../playwright.config';

/**
 * Mints a fresh single-use onboarding link before the suite runs. Without this
 * the welcome-link spec only passes once, because using the link burns it.
 */
export default function globalSetup() {
  const php = process.env.PHP_BIN || 'php';
  const script = path.join(__dirname, 'fixtures', 'make-welcome.php');
  try {
    const out = execFileSync(php, [script, APP_URL], { encoding: 'utf8' });
    console.log(out.trim());
  } catch (e: any) {
    console.warn(`welcome fixture could not be generated - that spec will skip.\n${e.message}`);
  }
}
