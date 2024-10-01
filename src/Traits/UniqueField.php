<?php

namespace Ferdinandbr\LaravelModelUniqueField\Traits;

use Ferdinandbr\LaravelModelUniqueField\Exceptions\MissingDynamicFieldException;

trait UniqueField
{
    public static function bootUniqueField()
    {
        // Evento para a criação de um novo item
        static::creating(function ($model) {
            $field = $model->getDynamicField();
            $model->{$field} = $model->prepareDynamicField('create', $field);
        });

        // Evento para a atualização de um item existente
        static::updating(function ($model) {
            $field = $model->getDynamicField();
            $model->{$field} = $model->prepareDynamicField('update', $field);
        });
    }

    /**
     * Prepara o campo dinâmico com base no método (create ou update).
     *
     * @param string $method
     * @param string $field
     * @return string
     */

    protected function prepareDynamicField($method, $field)
    {
        // Remove qualquer sufixo de número existente
        $input = preg_replace('/#\d+$/', '', $this->{$field});

        if ($method === 'create') {
            if ($this->dynamicFieldAlreadyExists($field) > 0) {
                $count = $this->dynamicFieldAlreadyExists($field) + 1;
                return $input . " #$count";
            }
        }

        if ($method === 'update') {
            $existingCount = $this->dynamicFieldAlreadyExists($field, true);
            if ($existingCount > 0) {
                return $input . " #" . ($existingCount + 1);
            }
        }

        return $input;
    }
    /**
     * Verifica se o campo dinâmico já existe.
     *
     * @param string $field
     * @return int
     */
    protected function dynamicFieldAlreadyExists($field, $ignoreCurrent = false)
    {
        $operator = 'LIKE';

        if (config('database.default') !== 'sqlite') {
            $operator = 'ILIKE';
        }

        $query = self::where($field, $operator, '%' . $this->{$field} . '%');

        if ($ignoreCurrent && $this->id) {
            $query->where('id', '!=', $this->id);
        }

        return $query->count();
    }

    /**
     * Retorna a posição do campo dinâmico.
     *
     * @param string $field
     * @return int
     */
    protected function dynamicFieldPosition(string $field): int
    {
        $name = $this->{$field};
        $currentId = $this->id;

        // Determina o operador correto dependendo do banco de dados
        $operator = 'LIKE';
        if (config('database.default') !== 'sqlite') {
            $operator = 'ILIKE';
        }

        // Query para buscar registros com o nome semelhante
        $query = self::where($field, $operator, '%' . $name . '%');

        // Se não houver outros registros, retorna 0
        if ($query->count() == 0) {
            return 0;
        }

        // Se for uma atualização, considera a posição do ID atual
        if ($currentId) {
            return $query->where('id', '<', $currentId)->count() + 1;
        }

        // Para novos registros, apenas conta os existentes
        return $query->count() + 1;
    }

    /**
     * Método para pegar o campo dinâmico no model.
     * O model que usar a trait deve definir a variável protected $dynamicField.
     *
     * @return string
     */
    protected function getDynamicField(): string
    {
        if (!property_exists($this, 'dynamicField') || empty($this->dynamicField)) {
            throw new MissingDynamicFieldException();
        }
        return $this->dynamicField;
    }
}
