<?php

namespace DTApi\Repository;

use Validator;
use Illuminate\Database\Eloquent\Model;
use DTApi\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseRepository
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $validationRules = [];

    /**
     * @var array
     */
    private $attributeNames = [];

    /**
     * @param Model $model
     * 
     * Constructor to intialize variables
     */
    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    /**
     * @param array $rules
     * 
     * This function is going to help to set validation rules
     */
    public function setValidationRules(array $rules)
    {
        $this->validationRules = $rules;
    }

    /**
     * @return array
     * 
     * This function is to get validation attribute names
     */
    public function validatorAttributeNames()
    {
        return $this->attributeNames;
    }

    /**
     * @param array $attributeNames
     * 
     * This function is to set validation attribute names
     */
    public function setAttributeNames(array $attributeNames)
    {
        $this->attributeNames = $attributeNames;
    }

    /**
     * @return Model|null
     * 
     * This function returns the model instance
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]|string
     * 
     * This function help to fetch all record for specific model
     */
    public function all()
    {
        try {
            return $this->model->all();
        } catch (ModelNotFoundException $ex) {
            return $ex->getMessage();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param integer $id
     * @return Model|null
     * @return string
     * 
     * This function helps you find record by id
     */
    public function find($id)
    {
        try {
            return $this->model->find($id);
        } catch (ModelNotFoundException $ex) {
            return $ex->getMessage();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param integer $id
     * @return Model|null|string
     * @return string
     * 
     * This function helps you fetch record from relational tables
     */
    public function with($array)
    {
        try {
            return $this->model->with($array);
        } catch (ModelNotFoundException $ex) {
            return $ex->getMessage();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param integer $id
     * @return Model|string
     * @throws ModelNotFoundException
     * 
     * This function helps you fetch record or gives an error if not found
     */
    public function findOrFail($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new ModelNotFoundException;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param string $slug
     * @return Model|string
     * @throws ModelNotFoundException
     * 
     * This function helps you find record by slug
     */
    public function findBySlug($slug)
    {
        try {
            return $this->model->where('slug', $slug)->first();
        } catch (ModelNotFoundException $ex) {
            throw new ModelNotFoundException;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|string 
     * 
     * This function help you query model
     */
    public function query()
    {
        try {
            return $this->model->query();
        } catch (ModelNotFoundException $ex) {
            return $ex->getMessage();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param array $attributes
     * @return Model|string
     * 
     * This function helps you to create new model instance with new attributes
     */
    public function instance(array $attributes = [])
    {
        try {
            $model = $this->model;
            return new $model($attributes);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param int|null $perPage
     * @return Model|string
     * 
     * This function is used to paginate model records
     */
    public function paginate($perPage = null)
    {
        try {
            return $this->model->paginate($perPage);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param string $key
     * @param string $where
     * @return Model|string
     * 
     * This function is used to filter records by conditions
     */
    public function where($key, $where)
    {
        try {
            return $this->model->where($key, $where);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param array $data
     * @param array|null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return Validator|string
     * 
     * This function will help you validate data input
     */
    public function validator(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        try {
            if (empty($rules)) {
                $rules = $this->validationRules;
            }

            return Validator::make($data, $rules, $messages, $customAttributes);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param array $data
     * @param null $rules
     * @param array $messages
     * @param array $customAttributes
     * @return bool|string
     * @throws ValidationException
     * 
     * This function help to validate data against rules set
     */
    public function validate(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        try {
            $validator = $this->validator($data, $rules, $messages, $customAttributes);
            return $this->_validate($validator);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param array $data
     * @return Model|string
     * 
     * This function is going to help you to create record.
     */
    public function create(array $data = [])
    {
        try {
            return $this->model->create($data);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param integer $id
     * @param array $data
     * @return Model
     * 
     * This function will help you to update record
     */
    public function update($id, array $data = [])
    {
        try {
            $instance = $this->findOrFail($id);
            $instance->update($data);

            return $instance;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param integer $id
     * @return Model|string
     * 
     * This function helps to delete record by id
     */
    public function delete($id)
    {
        try {
            $model = $this->findOrFail($id);
            $model->delete();
            return $model;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @param \Illuminate\Validation\Validator $validator
     * @return bool|string
     * @throws ValidationException|Exception
     * 
     * This function help you validate using attributeNames
     */
    protected function _validate(Validator $validator)
    {
        try {
            if (!empty($attributeNames = $this->validatorAttributeNames())) {
                $validator->setAttributeNames($attributeNames);
            }

            if ($validator->fails()) {
                throw (new ValidationException)->setValidator($validator);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        return true;
    }
}
