import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/login');
  await page.getByRole('textbox', { name: 'Електронна пошта' }).click();
  await page.getByRole('textbox', { name: 'Електронна пошта' }).click();
  await page.getByRole('textbox', { name: 'Електронна пошта' }).fill('johndoe@example.com');
  await page.getByRole('textbox', { name: 'Пароль' }).click();
  await page.getByRole('textbox', { name: 'Пароль' }).click();
  await page.getByRole('textbox', { name: 'Пароль' }).fill('secret');
  await page.getByRole('checkbox', { name: 'Запам\'ятати мене' }).check();
  await page.getByRole('button', { name: 'Увійти' }).click();
  await page.getByRole('link', { name: 'Студенти' }).click();
  await page.getByRole('link', { name: 'Створити студента' }).click();
  await page.getByRole('textbox', { name: 'Ім\'я' }).click();
  await page.getByRole('textbox', { name: 'Ім\'я' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Ім\'я' }).fill('М');
  await page.getByRole('textbox', { name: 'Ім\'я' }).press('CapsLock');
  await page.getByRole('textbox', { name: 'Ім\'я' }).fill('Максим');
  await page.goto('http://127.0.0.1:8000/students/create');
  await page.getByRole('button', { name: 'Створити студента' }).click();
  await page.getByRole('paragraph').filter({ hasText: 'multiple_errors' }).click();
  await expect(page.locator('#app')).toContainText('multiple_errors');
});