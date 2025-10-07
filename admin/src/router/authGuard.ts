import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router';
import { ref } from 'vue';
import fetchWithCors from '../utils/fetchWithCors';

export const isAuthenticated = ref(false);

// Функция проверки авторизации
export async function checkAuthGuard(
  _to: RouteLocationNormalized,
  _from: RouteLocationNormalized,
  next: NavigationGuardNext
) {
  try {
    // Проверяем авторизацию через API
    const response = await fetchWithCors(
      '/server/php/admin/api/auth/check-auth.php'
    );

    if (response.authenticated) {
      // Пользователь авторизован - разрешаем доступ
      isAuthenticated.value = true;
      next();
    } else {

      window.location.href = '/login';
    }
  } catch (error) {
    isAuthenticated.value = false;
    console.error('Ошибка проверки авторизации:', error);
  }
}

// Функция для проверки авторизации без перенаправления
export async function checkAuthStatus(): Promise<boolean> {
  try {
    const response = await fetchWithCors(
      '/server/php/admin/api/auth/check-auth.php'
    );
    isAuthenticated.value = response.authenticated;
    return response.data;
  } catch (error) {
    isAuthenticated.value = false;
    console.error('Ошибка проверки статуса авторизации:', error);
    return false;
  }
}

// Функция для принудительного обновления статуса
export async function refreshAuthStatus() {
  return await checkAuthStatus();
}
