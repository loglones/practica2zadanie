<?php
namespace App\Validators;

class RegisterValidator
{
    private array $errors = [];

    public function validate(array $data): bool
    {
        $this->errors = [];

        // Проверка имени (только русские буквы)
        $this->validateName($data['name'] ?? '');

        // Проверка логина (латиница и цифры)
        $this->validateLogin($data['login'] ?? '');

        // Проверка пароля (латиница и цифры)
        $this->validatePassword($data['password'] ?? '');

        return empty($this->errors);
    }

    private function validateName(string $name): void
    {
        if (empty($name)) {
            $this->errors['name'][] = 'Имя обязательно для заполнения';
            return;
        }

        // Только русские буквы и пробелы
        if (!preg_match('/^[а-яёА-ЯЁ\s]+$/u', $name)) {
            $this->errors['name'][] = 'Имя должно содержать только русские буквы';
        }

        // Длина имени 2-50 символов
        $length = mb_strlen($name, 'UTF-8');
        if ($length < 2 || $length > 50) {
            $this->errors['name'][] = 'Имя должно быть от 2 до 50 символов';
        }
    }

    private function validateLogin(string $login): void
    {
        if (empty($login)) {
            $this->errors['login'][] = 'Логин обязателен для заполнения';
            return;
        }

        // Латиница и цифры
        if (!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
            $this->errors['login'][] = 'Логин должен содержать только латинские буквы и цифры';
        }

        // Длина логина 4-20 символов
        $length = strlen($login);
        if ($length < 4 || $length > 20) {
            $this->errors['login'][] = 'Логин должен быть от 4 до 20 символов';
        }
    }

    private function validatePassword(string $password): void
    {
        if (empty($password)) {
            $this->errors['password'][] = 'Пароль обязателен для заполнения';
            return;
        }

        // Латиница и цифры
        if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            $this->errors['password'][] = 'Пароль должен содержать только латинские буквы и цифры';
        }

        // Длина пароля 6-30 символов
        $length = strlen($password);
        if ($length < 6 || $length > 30) {
            $this->errors['password'][] = 'Пароль должен быть от 6 до 30 символов';
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(): ?string
    {
        foreach (['name', 'login', 'password'] as $field) {
            if (!empty($this->errors[$field])) {
                return $this->errors[$field][0];
            }
        }
        return null;
    }
}