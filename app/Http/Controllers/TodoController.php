<?php
namespace App\Http\Controllers;

class TodoController extends Controller
{
    /**
     * Главная страница
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }
}