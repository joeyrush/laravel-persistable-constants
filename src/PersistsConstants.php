<?php
namespace JoeyRush\PersistableConstants;

trait PersistsConstants
{
    public static function persistConstants()
    {
        $reflectedClass = new \ReflectionClass(__CLASS__);
        $constants = $reflectedClass->getConstants();

        $instance = new static;

        $table = \DB::table($instance->constantsTable);
        $table->truncate();
        $table->insert($instance->formatConstantsForDB($constants));
    }

    /**
     * @param $constants
     * @return array
     */
    public function formatConstantsForDB(array $constants): array
    {
        return collect($constants)->map(function ($id, $key) {
            return [
                'id' => $id,
                'name' => $this->transform($key),
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s')
            ];
        })->toArray();
    }

    public function find($value)
    {
        $reflectedClass = new \ReflectionClass(__CLASS__);
        $constants = $reflectedClass->getConstants();

        $flipped = array_flip($constants);

        return isset($flipped[$value]) ? $this->transform($flipped[$value]) : null;
    }

    public function transform($constant)
    {
        return strtolower($constant);
    }
}
