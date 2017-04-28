<?php
/**
 * @file
 * Contains \MicroServiceModelUtilsTest.
 */

use Illuminate\Database\Eloquent\Model;
use LushDigital\MicroServiceModelUtils\Models\MicroServiceBaseModel;

/**
 * Test the MicroService model utils functionality.
 */
class MicroServiceModelUtilsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Expected database table name.
     *
     * @var string
     */
    protected $expectedTableName = 'examples';

    /**
     * Expected attribute cache keys.
     *
     * @var array
     */
    protected $expectedAttributeCacheKeys = [];

    /**
     * Expected attribute cache keys for the primary key model.
     *
     * @var array
     */
    protected $expectedPrimaryKeyAttributeCacheKeys = [];

    /**
     * Expected model cache keys.
     *
     * @var array
     */
    protected $expectedModelCacheKeys = ['examples:index'];

    /**
     * Expected model cache keys for the primary key model.
     *
     * @var array
     */
    protected $expectedPrimaryKeyModelCacheKeys = [
        'primary_key_examples:index',
        'primary_key_examples:1'
    ];

    /**
     * Date format for testing.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Test the base model functionality.
     *
     * @return void
     */
    public function testBaseModel()
    {
        // Test the table name.
        $this->assertEquals($this->expectedTableName, Example::getTableName());

        // Test the cache keys.
        $model = new Example;
        $this->assertEquals($this->expectedAttributeCacheKeys, $model->getAttributeCacheKeys());

        // Alter the cache keys and test again.
        $this->expectedAttributeCacheKeys = ['name'];
        $model->setAttributeCacheKeys($this->expectedAttributeCacheKeys);
        $this->assertEquals($this->expectedAttributeCacheKeys, $model->getAttributeCacheKeys());

        // Test the cache keys with a primary key.
        $primaryKeyModel = new PrimaryKeyExample();
        $this->assertEquals($this->expectedPrimaryKeyAttributeCacheKeys, $primaryKeyModel->getAttributeCacheKeys());

        // Alter the cache keys and test again.
        $this->expectedPrimaryKeyAttributeCacheKeys = ['name'];
        $primaryKeyModel->setAttributeCacheKeys($this->expectedPrimaryKeyAttributeCacheKeys);
        $this->assertEquals($this->expectedPrimaryKeyAttributeCacheKeys, $primaryKeyModel->getAttributeCacheKeys());
    }

    /**
     * Test the cache handling trait.
     *
     * @return void
     */
    public function testCacheTrait()
    {
        // Test the model cache keys.
        $model = new Example;
        $exampleThing = new AnotherExample;
        $this->assertEquals($this->expectedModelCacheKeys, $exampleThing->getModelCacheKeys($model));

        // Alter the cache keys and test again.
        $model->name = 'test';
        $this->expectedAttributeCacheKeys = ['name'];
        $this->expectedModelCacheKeys[] = 'examples:name:test';
        $model->setAttributeCacheKeys($this->expectedAttributeCacheKeys);
        $this->assertEquals($this->expectedModelCacheKeys, $exampleThing->getModelCacheKeys($model));

        // Test the model cache keys with a primary key.
        $primaryKeyModel = new PrimaryKeyExample;
        $primaryKeyModel->name = 'testing';
        $exampleThing = new AnotherExample;
        $this->assertEquals($this->expectedPrimaryKeyModelCacheKeys, $exampleThing->getModelCacheKeys($primaryKeyModel));

        // Alter the cache keys and test again.
        $this->expectedPrimaryKeyAttributeCacheKeys = ['name'];
        $this->expectedPrimaryKeyModelCacheKeys[] = 'primary_key_examples:name:testing';
        $primaryKeyModel->setAttributeCacheKeys($this->expectedAttributeCacheKeys);
        $this->assertEquals($this->expectedPrimaryKeyModelCacheKeys, $exampleThing->getModelCacheKeys($primaryKeyModel));
    }

    /**
     * Test the ISO8601 Date handling trait.
     *
     * @return void
     */
    public function testISO8601DatesTrait()
    {
        // Create a new model instance.
        $model = new Example;
        $model->setDateFormat($this->dateFormat);

        // Test the time stamps.
        $now = new DateTime;
        $this->assertEquals($now->format(DateTime::ATOM), $model->getCreatedAtAttribute($now->format($this->dateFormat)));
        $this->assertEquals($now->format(DateTime::ATOM), $model->getUpdatedAtAttribute($now->format($this->dateFormat)));
    }
}

/**
 * Example model class.
 */
class Example extends MicroServiceBaseModel
{
    use \LushDigital\MicroServiceModelUtils\Traits\MicroServiceISO8601DatesTrait;
}

/**
 * Example model class with a primary key value.
 */
class PrimaryKeyExample extends MicroServiceBaseModel
{
    use \LushDigital\MicroServiceModelUtils\Traits\MicroServiceISO8601DatesTrait;

    protected $attributes = [
        'id' => 1,
    ];
}

/**
 * An example class to test the cache handling trait.
 */
class AnotherExample
{
    use \LushDigital\MicroServiceModelUtils\Traits\MicroServiceCacheTrait;
}
