import { test, expect } from '@playwright/test';


test.beforeEach(async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/login');
  await page.getByRole('textbox', { name: 'Електронна пошта' }).fill('johndoe@example.com');
  await page.getByRole('textbox', { name: 'Пароль' }).fill('secret');
  await page.getByRole('button', { name: 'Увійти' }).click();
});

test('Існування подій', async ({ page }) => {
  await page.getByRole('link', { name: 'Події', exact: true }).click();
  await expect(page.getByRole('heading', { name: 'Події' })).toBeVisible();
  
});

test('Студенти', async ({ page }) => {
  await page.getByRole('link', { name: 'Студенти', exact: true }).click();
  await expect(page.getByRole('heading', { name: 'Студенти' })).toBeVisible();
});


test('Створення викладачив', async ({ page }) => {
  await page.getByRole('link', { name: 'Вчителі' }).click();
  await page.getByRole('link', { name: 'Створити вчителя' }).click();
  await page.getByRole('textbox', { name: 'Ім\'я' }).fill('Максим');
  await page.getByRole('textbox', { name: 'По батькові' }).click();
  await page.getByRole('textbox', { name: 'По батькові' }).fill('Олександрович');
  await page.getByRole('textbox', { name: 'Прізвище' }).click();
  await page.getByRole('textbox', { name: 'Прізвище' }).fill('Лисак');
  await page.getByRole('button', { name: 'Створити вчителя' }).click();
  await expect(page.getByText('Teacher created.')).toBeVisible();
});

test('Батьки', async ({ page }) => {await page.getByRole('link', { name: 'Батьки' }).click();
await page.getByRole('link', { name: 'Створити батьків' }).click();
await page.getByLabel('Тип батьків').selectOption('father');
await page.getByRole('textbox', { name: 'Ім\'я' }).click();
await page.getByRole('textbox', { name: 'Ім\'я' }).fill('Олександр');
await page.getByRole('textbox', { name: 'По батькові' }).click();
await page.getByRole('textbox', { name: 'По батькові' }).fill('Олександрович');
await page.getByRole('textbox', { name: 'Прізвище' }).click();
await page.getByRole('textbox', { name: 'Прізвище' }).fill('Іванов');
await page.getByLabel('Область').selectOption('Одеська область');
await page.getByLabel('Місто').selectOption('Одеса');

await page.getByRole('textbox', { name: 'Поштовий індекс' }).click();
await page.getByRole('textbox', { name: 'Поштовий індекс' }).fill('123456');
await page.getByRole('button', { name: 'Створити батьків' }).click();
await expect(page.getByText('Батьків створено')).toBeVisible();
});
