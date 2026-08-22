<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GameCategoryModel;
use App\Models\GameModel;
use App\Models\SettingModel;

class Games extends BaseController
{
    protected $gameModel;
    protected $categoryModel;
    protected $settingModel;

    public function __construct()
    {
        $this->gameModel     = new GameModel();
        $this->categoryModel = new GameCategoryModel();
        $this->settingModel  = new SettingModel();
    }

    public function index()
    {
        $games = $this->gameModel
            ->select('games.*, game_categories.name as category_name')
            ->join('game_categories', 'game_categories.id = games.category_id', 'left')
            ->orderBy('games.sort_order', 'ASC')
            ->findAll();

        $settings = $this->settingModel->getAllSettings();

        return view('admin/games/index', [
            'title'    => 'Kelola Game & Form Input - ' . ($settings['site_name'] ?? 'Norvago'),
            'games'    => $games,
            'settings' => $settings,
        ]);
    }

    public function create()
    {
        $categories = $this->categoryModel->findAll();
        $settings   = $this->settingModel->getAllSettings();

        return view('admin/games/form', [
            'title'      => 'Tambah Game Baru',
            'categories' => $categories,
            'game'       => null,
            'settings'   => $settings,
        ]);
    }

    public function edit(int $id)
    {
        $game = $this->gameModel->find($id);
        if (!$game) {
            return redirect()->to('/admin/games')->with('error', 'Game tidak ditemukan');
        }

        $categories = $this->categoryModel->findAll();
        $settings   = $this->settingModel->getAllSettings();

        return view('admin/games/form', [
            'title'      => 'Edit Game: ' . $game['name'],
            'categories' => $categories,
            'game'       => $game,
            'settings'   => $settings,
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $name = trim((string) $this->request->getPost('name'));
        $slug = trim((string) $this->request->getPost('slug')) ?: url_title($name, '-', true);

        $data = [
            'category_id'                => (int) $this->request->getPost('category_id'),
            'name'                       => $name,
            'slug'                       => $slug,
            'subtitle'                   => $this->request->getPost('subtitle'),
            'developer'                  => $this->request->getPost('developer'),
            'image_url'                  => $this->request->getPost('image_url'),
            'banner_url'                 => $this->request->getPost('banner_url'),
            'instructions'               => $this->request->getPost('instructions'),
            'target_input_type'          => $this->request->getPost('target_input_type') ?: 'single',
            'target_input_label_1'       => $this->request->getPost('target_input_label_1') ?: 'User ID',
            'target_input_label_2'       => $this->request->getPost('target_input_label_2'),
            'target_input_placeholder_1' => $this->request->getPost('target_input_placeholder_1'),
            'target_input_placeholder_2' => $this->request->getPost('target_input_placeholder_2'),
            'server_list'                => $this->request->getPost('server_list'),
            'check_id_endpoint'          => $this->request->getPost('check_id_endpoint'),
            'is_popular'                 => $this->request->getPost('is_popular') ? 1 : 0,
            'is_active'                  => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order'                 => (int) $this->request->getPost('sort_order'),
        ];

        if ($id) {
            $this->gameModel->update($id, $data);
            $msg = 'Game berhasil diperbarui!';
        } else {
            $this->gameModel->insert($data);
            $msg = 'Game baru berhasil ditambahkan!';
        }

        return redirect()->to('/admin/games')->with('success', $msg);
    }

    public function delete(int $id)
    {
        $this->gameModel->delete($id);
        return redirect()->to('/admin/games')->with('success', 'Game berhasil dihapus!');
    }
}
