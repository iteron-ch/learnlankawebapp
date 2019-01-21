<?php

namespace App\Repositories;
use Carbon\Carbon;
abstract class BaseRepository {

    /**
     * The Model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Get number of records.
     *
     * @return array
     */
    public function getNumber() {
        $total = $this->model->count();

        $new = $this->model->whereSeen(0)->count();

        return compact('total', 'new');
    }

    /**
     * Destroy a model.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id) {
        $this->getById($id)->delete();
    }

    /**
     * Get Model by id.
     *
     * @param  int  $id
     * @return App\Models\Model
     */
    public function getById($id) {
        return $this->model->findOrFail($id);
    }

    /**
     * Delete a model row.
     *
     * @param  array  $inputs
     * @param  $id 
     * @return void
     */
    public function destroyRow($inputs) {
        $model = $this->model->where('id', '=', $inputs['id'])->first();
        $model->updated_by = $inputs['updated_by'];
        $model->deleted_at = Carbon::now()->toDateTimeString();
        $model->status = DELETED; // deleted
        $model->save();
    }

}
