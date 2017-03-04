<?php

namespace AcademiaDigital\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use AcademiaDigital\AcademiaDigital\Customer as ChildCustomer;
use AcademiaDigital\AcademiaDigital\CustomerQuery as ChildCustomerQuery;
use AcademiaDigital\AcademiaDigital\Training as ChildTraining;
use AcademiaDigital\AcademiaDigital\TrainingExercise as ChildTrainingExercise;
use AcademiaDigital\AcademiaDigital\TrainingExerciseQuery as ChildTrainingExerciseQuery;
use AcademiaDigital\AcademiaDigital\TrainingQuery as ChildTrainingQuery;
use AcademiaDigital\AcademiaDigital\Map\TrainingExerciseTableMap;
use AcademiaDigital\AcademiaDigital\Map\TrainingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'training' table.
 *
 *
 *
 * @package    propel.generator.AcademiaDigital.AcademiaDigital.Base
 */
abstract class Training implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\AcademiaDigital\\AcademiaDigital\\Map\\TrainingTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the customer_id field.
     *
     * @var        int
     */
    protected $customer_id;

    /**
     * The value for the exercise_id field.
     *
     * @var        int
     */
    protected $exercise_id;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the modified_at field.
     *
     * @var        DateTime
     */
    protected $modified_at;

    /**
     * The value for the exercise_id1 field.
     *
     * @var        int
     */
    protected $exercise_id1;

    /**
     * @var        ChildCustomer
     */
    protected $aCustomer;

    /**
     * @var        ObjectCollection|ChildTrainingExercise[] Collection to store aggregation of ChildTrainingExercise objects.
     */
    protected $collTrainingExercises;
    protected $collTrainingExercisesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTrainingExercise[]
     */
    protected $trainingExercisesScheduledForDeletion = null;

    /**
     * Initializes internal state of AcademiaDigital\AcademiaDigital\Base\Training object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Training</code> instance.  If
     * <code>obj</code> is an instance of <code>Training</code>, delegates to
     * <code>equals(Training)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Training The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [customer_id] column value.
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Get the [exercise_id] column value.
     *
     * @return int
     */
    public function getExerciseId()
    {
        return $this->exercise_id;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [modified_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getModifiedAt($format = NULL)
    {
        if ($format === null) {
            return $this->modified_at;
        } else {
            return $this->modified_at instanceof \DateTimeInterface ? $this->modified_at->format($format) : null;
        }
    }

    /**
     * Get the [exercise_id1] column value.
     *
     * @return int
     */
    public function getExerciseId1()
    {
        return $this->exercise_id1;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[TrainingTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[TrainingTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [customer_id] column.
     *
     * @param int $v new value
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function setCustomerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->customer_id !== $v) {
            $this->customer_id = $v;
            $this->modifiedColumns[TrainingTableMap::COL_CUSTOMER_ID] = true;
        }

        if ($this->aCustomer !== null && $this->aCustomer->getId() !== $v) {
            $this->aCustomer = null;
        }

        return $this;
    } // setCustomerId()

    /**
     * Set the value of [exercise_id] column.
     *
     * @param int $v new value
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function setExerciseId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->exercise_id !== $v) {
            $this->exercise_id = $v;
            $this->modifiedColumns[TrainingTableMap::COL_EXERCISE_ID] = true;
        }

        return $this;
    } // setExerciseId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TrainingTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [modified_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function setModifiedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->modified_at !== null || $dt !== null) {
            if ($this->modified_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->modified_at->format("Y-m-d H:i:s.u")) {
                $this->modified_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TrainingTableMap::COL_MODIFIED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setModifiedAt()

    /**
     * Set the value of [exercise_id1] column.
     *
     * @param int $v new value
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function setExerciseId1($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->exercise_id1 !== $v) {
            $this->exercise_id1 = $v;
            $this->modifiedColumns[TrainingTableMap::COL_EXERCISE_ID1] = true;
        }

        return $this;
    } // setExerciseId1()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TrainingTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TrainingTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TrainingTableMap::translateFieldName('CustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : TrainingTableMap::translateFieldName('ExerciseId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->exercise_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : TrainingTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : TrainingTableMap::translateFieldName('ModifiedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->modified_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : TrainingTableMap::translateFieldName('ExerciseId1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->exercise_id1 = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = TrainingTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\AcademiaDigital\\AcademiaDigital\\Training'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aCustomer !== null && $this->customer_id !== $this->aCustomer->getId()) {
            $this->aCustomer = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TrainingTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTrainingQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCustomer = null;
            $this->collTrainingExercises = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Training::setDeleted()
     * @see Training::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TrainingTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTrainingQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TrainingTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                TrainingTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCustomer !== null) {
                if ($this->aCustomer->isModified() || $this->aCustomer->isNew()) {
                    $affectedRows += $this->aCustomer->save($con);
                }
                $this->setCustomer($this->aCustomer);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->trainingExercisesScheduledForDeletion !== null) {
                if (!$this->trainingExercisesScheduledForDeletion->isEmpty()) {
                    \AcademiaDigital\AcademiaDigital\TrainingExerciseQuery::create()
                        ->filterByPrimaryKeys($this->trainingExercisesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->trainingExercisesScheduledForDeletion = null;
                }
            }

            if ($this->collTrainingExercises !== null) {
                foreach ($this->collTrainingExercises as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[TrainingTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TrainingTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TrainingTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(TrainingTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(TrainingTableMap::COL_CUSTOMER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'customer_id';
        }
        if ($this->isColumnModified(TrainingTableMap::COL_EXERCISE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'exercise_id';
        }
        if ($this->isColumnModified(TrainingTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(TrainingTableMap::COL_MODIFIED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'modified_at';
        }
        if ($this->isColumnModified(TrainingTableMap::COL_EXERCISE_ID1)) {
            $modifiedColumns[':p' . $index++]  = 'exercise_id1';
        }

        $sql = sprintf(
            'INSERT INTO training (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'customer_id':
                        $stmt->bindValue($identifier, $this->customer_id, PDO::PARAM_INT);
                        break;
                    case 'exercise_id':
                        $stmt->bindValue($identifier, $this->exercise_id, PDO::PARAM_INT);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'modified_at':
                        $stmt->bindValue($identifier, $this->modified_at ? $this->modified_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'exercise_id1':
                        $stmt->bindValue($identifier, $this->exercise_id1, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TrainingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getCustomerId();
                break;
            case 3:
                return $this->getExerciseId();
                break;
            case 4:
                return $this->getCreatedAt();
                break;
            case 5:
                return $this->getModifiedAt();
                break;
            case 6:
                return $this->getExerciseId1();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Training'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Training'][$this->hashCode()] = true;
        $keys = TrainingTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getCustomerId(),
            $keys[3] => $this->getExerciseId(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getModifiedAt(),
            $keys[6] => $this->getExerciseId1(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCustomer) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'customer';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'customer';
                        break;
                    default:
                        $key = 'Customer';
                }

                $result[$key] = $this->aCustomer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collTrainingExercises) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'trainingExercises';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'training_exercises';
                        break;
                    default:
                        $key = 'TrainingExercises';
                }

                $result[$key] = $this->collTrainingExercises->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\AcademiaDigital\AcademiaDigital\Training
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TrainingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\AcademiaDigital\AcademiaDigital\Training
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setCustomerId($value);
                break;
            case 3:
                $this->setExerciseId($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
                $this->setModifiedAt($value);
                break;
            case 6:
                $this->setExerciseId1($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = TrainingTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCustomerId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setExerciseId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCreatedAt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setModifiedAt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setExerciseId1($arr[$keys[6]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TrainingTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TrainingTableMap::COL_ID)) {
            $criteria->add(TrainingTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(TrainingTableMap::COL_NAME)) {
            $criteria->add(TrainingTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(TrainingTableMap::COL_CUSTOMER_ID)) {
            $criteria->add(TrainingTableMap::COL_CUSTOMER_ID, $this->customer_id);
        }
        if ($this->isColumnModified(TrainingTableMap::COL_EXERCISE_ID)) {
            $criteria->add(TrainingTableMap::COL_EXERCISE_ID, $this->exercise_id);
        }
        if ($this->isColumnModified(TrainingTableMap::COL_CREATED_AT)) {
            $criteria->add(TrainingTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(TrainingTableMap::COL_MODIFIED_AT)) {
            $criteria->add(TrainingTableMap::COL_MODIFIED_AT, $this->modified_at);
        }
        if ($this->isColumnModified(TrainingTableMap::COL_EXERCISE_ID1)) {
            $criteria->add(TrainingTableMap::COL_EXERCISE_ID1, $this->exercise_id1);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildTrainingQuery::create();
        $criteria->add(TrainingTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \AcademiaDigital\AcademiaDigital\Training (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setCustomerId($this->getCustomerId());
        $copyObj->setExerciseId($this->getExerciseId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setModifiedAt($this->getModifiedAt());
        $copyObj->setExerciseId1($this->getExerciseId1());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getTrainingExercises() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTrainingExercise($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \AcademiaDigital\AcademiaDigital\Training Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildCustomer object.
     *
     * @param  ChildCustomer $v
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCustomer(ChildCustomer $v = null)
    {
        if ($v === null) {
            $this->setCustomerId(NULL);
        } else {
            $this->setCustomerId($v->getId());
        }

        $this->aCustomer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCustomer object, it will not be re-added.
        if ($v !== null) {
            $v->addTraining($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCustomer object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCustomer The associated ChildCustomer object.
     * @throws PropelException
     */
    public function getCustomer(ConnectionInterface $con = null)
    {
        if ($this->aCustomer === null && ($this->customer_id !== null)) {
            $this->aCustomer = ChildCustomerQuery::create()->findPk($this->customer_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCustomer->addTrainings($this);
             */
        }

        return $this->aCustomer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('TrainingExercise' == $relationName) {
            return $this->initTrainingExercises();
        }
    }

    /**
     * Clears out the collTrainingExercises collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTrainingExercises()
     */
    public function clearTrainingExercises()
    {
        $this->collTrainingExercises = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTrainingExercises collection loaded partially.
     */
    public function resetPartialTrainingExercises($v = true)
    {
        $this->collTrainingExercisesPartial = $v;
    }

    /**
     * Initializes the collTrainingExercises collection.
     *
     * By default this just sets the collTrainingExercises collection to an empty array (like clearcollTrainingExercises());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTrainingExercises($overrideExisting = true)
    {
        if (null !== $this->collTrainingExercises && !$overrideExisting) {
            return;
        }

        $collectionClassName = TrainingExerciseTableMap::getTableMap()->getCollectionClassName();

        $this->collTrainingExercises = new $collectionClassName;
        $this->collTrainingExercises->setModel('\AcademiaDigital\AcademiaDigital\TrainingExercise');
    }

    /**
     * Gets an array of ChildTrainingExercise objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTraining is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTrainingExercise[] List of ChildTrainingExercise objects
     * @throws PropelException
     */
    public function getTrainingExercises(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTrainingExercisesPartial && !$this->isNew();
        if (null === $this->collTrainingExercises || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTrainingExercises) {
                // return empty collection
                $this->initTrainingExercises();
            } else {
                $collTrainingExercises = ChildTrainingExerciseQuery::create(null, $criteria)
                    ->filterByTraining($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTrainingExercisesPartial && count($collTrainingExercises)) {
                        $this->initTrainingExercises(false);

                        foreach ($collTrainingExercises as $obj) {
                            if (false == $this->collTrainingExercises->contains($obj)) {
                                $this->collTrainingExercises->append($obj);
                            }
                        }

                        $this->collTrainingExercisesPartial = true;
                    }

                    return $collTrainingExercises;
                }

                if ($partial && $this->collTrainingExercises) {
                    foreach ($this->collTrainingExercises as $obj) {
                        if ($obj->isNew()) {
                            $collTrainingExercises[] = $obj;
                        }
                    }
                }

                $this->collTrainingExercises = $collTrainingExercises;
                $this->collTrainingExercisesPartial = false;
            }
        }

        return $this->collTrainingExercises;
    }

    /**
     * Sets a collection of ChildTrainingExercise objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $trainingExercises A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildTraining The current object (for fluent API support)
     */
    public function setTrainingExercises(Collection $trainingExercises, ConnectionInterface $con = null)
    {
        /** @var ChildTrainingExercise[] $trainingExercisesToDelete */
        $trainingExercisesToDelete = $this->getTrainingExercises(new Criteria(), $con)->diff($trainingExercises);


        $this->trainingExercisesScheduledForDeletion = $trainingExercisesToDelete;

        foreach ($trainingExercisesToDelete as $trainingExerciseRemoved) {
            $trainingExerciseRemoved->setTraining(null);
        }

        $this->collTrainingExercises = null;
        foreach ($trainingExercises as $trainingExercise) {
            $this->addTrainingExercise($trainingExercise);
        }

        $this->collTrainingExercises = $trainingExercises;
        $this->collTrainingExercisesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related TrainingExercise objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related TrainingExercise objects.
     * @throws PropelException
     */
    public function countTrainingExercises(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTrainingExercisesPartial && !$this->isNew();
        if (null === $this->collTrainingExercises || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTrainingExercises) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTrainingExercises());
            }

            $query = ChildTrainingExerciseQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTraining($this)
                ->count($con);
        }

        return count($this->collTrainingExercises);
    }

    /**
     * Method called to associate a ChildTrainingExercise object to this object
     * through the ChildTrainingExercise foreign key attribute.
     *
     * @param  ChildTrainingExercise $l ChildTrainingExercise
     * @return $this|\AcademiaDigital\AcademiaDigital\Training The current object (for fluent API support)
     */
    public function addTrainingExercise(ChildTrainingExercise $l)
    {
        if ($this->collTrainingExercises === null) {
            $this->initTrainingExercises();
            $this->collTrainingExercisesPartial = true;
        }

        if (!$this->collTrainingExercises->contains($l)) {
            $this->doAddTrainingExercise($l);

            if ($this->trainingExercisesScheduledForDeletion and $this->trainingExercisesScheduledForDeletion->contains($l)) {
                $this->trainingExercisesScheduledForDeletion->remove($this->trainingExercisesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTrainingExercise $trainingExercise The ChildTrainingExercise object to add.
     */
    protected function doAddTrainingExercise(ChildTrainingExercise $trainingExercise)
    {
        $this->collTrainingExercises[]= $trainingExercise;
        $trainingExercise->setTraining($this);
    }

    /**
     * @param  ChildTrainingExercise $trainingExercise The ChildTrainingExercise object to remove.
     * @return $this|ChildTraining The current object (for fluent API support)
     */
    public function removeTrainingExercise(ChildTrainingExercise $trainingExercise)
    {
        if ($this->getTrainingExercises()->contains($trainingExercise)) {
            $pos = $this->collTrainingExercises->search($trainingExercise);
            $this->collTrainingExercises->remove($pos);
            if (null === $this->trainingExercisesScheduledForDeletion) {
                $this->trainingExercisesScheduledForDeletion = clone $this->collTrainingExercises;
                $this->trainingExercisesScheduledForDeletion->clear();
            }
            $this->trainingExercisesScheduledForDeletion[]= clone $trainingExercise;
            $trainingExercise->setTraining(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Training is new, it will return
     * an empty collection; or if this Training has previously
     * been saved, it will retrieve related TrainingExercises from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Training.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildTrainingExercise[] List of ChildTrainingExercise objects
     */
    public function getTrainingExercisesJoinExercise(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildTrainingExerciseQuery::create(null, $criteria);
        $query->joinWith('Exercise', $joinBehavior);

        return $this->getTrainingExercises($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCustomer) {
            $this->aCustomer->removeTraining($this);
        }
        $this->id = null;
        $this->name = null;
        $this->customer_id = null;
        $this->exercise_id = null;
        $this->created_at = null;
        $this->modified_at = null;
        $this->exercise_id1 = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collTrainingExercises) {
                foreach ($this->collTrainingExercises as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collTrainingExercises = null;
        $this->aCustomer = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TrainingTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}