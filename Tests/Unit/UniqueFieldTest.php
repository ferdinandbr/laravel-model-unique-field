<?php

namespace Ferdinandbr\LaravelModelUniqueField\Tests\Unit;

use Ferdinandbr\LaravelModelUniqueField\Exceptions\MissingDynamicFieldException;
use Ferdinandbr\LaravelModelUniqueField\Models\TestModel;
use Ferdinandbr\LaravelModelUniqueField\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UniqueFieldTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'sqlite']);
    }

    /**
     * Testa a criação de um campo único.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldAlreadyExists
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */

    public function testCreatingUniqueField()
    {
        $model = TestModel::create(['name' => 'Name A']);

        $this->assertEquals('Name A', $model->name);

        $model2 = TestModel::create(['name' => 'Name A']);

        $this->assertEquals('Name A #2', $model2->name);
    }

    /**
     * Testa a atualização de um campo único.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */

    public function testUpdatingUniqueField()
    {
        $model = TestModel::create(['name' => 'Name A']);
        $model2 = TestModel::create(['name' => 'Name B']);
        $model3 = TestModel::create(['name' => 'Name B']);
        $model4 = TestModel::create(['name' => 'Name B']);

        $model->update(['name' => 'Name B']);

        $this->assertEquals('Name B #4', $model->name);
    }
    /**
     * @covers Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField
     */

    public function testMissingDynamicFieldException()
    {
        $this->expectException(MissingDynamicFieldException::class);

        $model = new class extends TestModel {
            protected $dynamicField;
        };

        $model->table = 'test_model';
        $model->fillable = ['name'];

        $model->create(['name' => 'Test Name']);
    }
    /**
     * Testa a atualização de um campo único.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */

    public function testUpdatingUniqueFieldAsSameValue()
    {
        $model = TestModel::create(['name' => 'Name A']);

        $model2 = TestModel::create(['name' => 'Name A']);

        $model2->update(['name' => 'Name A']);

        $this->assertEquals('Name A #2', $model2->name);
    }

    /**
     * Testa a criação de múltiplos registros com o mesmo valor de campo.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */
    public function testCreatingMultipleDuplicateFields()
    {
        $model = TestModel::create(['name' => 'Name A']);
        $model2 = TestModel::create(['name' => 'Name A']);
        $model3 = TestModel::create(['name' => 'Name A']);
        $model4 = TestModel::create(['name' => 'Name A']);

        $this->assertEquals('Name A', $model->name);
        $this->assertEquals('Name A #2', $model2->name);
        $this->assertEquals('Name A #3', $model3->name);
        $this->assertEquals('Name A #4', $model4->name);
    }

    /**
     * Testa a atualização de um valor para outro que já existe.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */
    public function testUpdatingFieldToExistingValue()
    {
        $model = TestModel::create(['name' => 'Name X']);
        $model2 = TestModel::create(['name' => 'Name A']);

        $model->update(['name' => 'Name A']);

        $this->assertEquals('Name A #2', $model->name);
    }

    /**
     * Testa a atualização de um campo para o mesmo valor sem gerar um sufixo adicional.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */
    public function testUpdatingFieldWithoutChangingValue()
    {
        $model = TestModel::create(['name' => 'Name A']);
        $model->update(['name' => 'Name A']);

        $this->assertEquals('Name A', $model->name);
    }

    /**
     * Testa a exclusão de um registro e criação subsequente com o mesmo nome.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */
    public function testDeletingRecordAndCreatingSameName()
    {
        $model = TestModel::create(['name' => 'Name A']);
        $model2 = TestModel::create(['name' => 'Name A']);

        $this->assertEquals('Name A', $model->name);
        $this->assertEquals('Name A #2', $model2->name);

        $model2->delete();

        $model3 = TestModel::create(['name' => 'Name A']);

        // Mesmo com o registro deletado, deve respeitar o sufixo
        $this->assertEquals('Name A #2', $model3->name);
    }

    /**
     * Testa a criação de campos com diferentes valores sem gerar sufixos.
     *
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::prepareDynamicField
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::dynamicFieldPosition
     * @covers \Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField::getDynamicField
     */
    public function testCreatingUniqueFieldsWithDifferentValues()
    {
        $model = TestModel::create(['name' => 'Name A']);
        $model2 = TestModel::create(['name' => 'Name B']);
        $model3 = TestModel::create(['name' => 'Name C']);

        $this->assertEquals('Name A', $model->name);
        $this->assertEquals('Name B', $model2->name);
        $this->assertEquals('Name C', $model3->name);
    }
}
