<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    /**
     * Валидирует DTO и возвращает JsonResponse с ошибками или null если всё ок
     */
    public function validate(object $dto): ?JsonResponse
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return new JsonResponse([
                'error' => 'Validation failed',
                'details' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        return null;
    }

    /**
     * Заполняет DTO из массива данных
     */
    public function populateDto(object $dto, array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }
    }
}
