<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShotRequest;
use App\Services\Interfaces\GameServiceInterface;
use App\Services\Interfaces\GridServiceInterface;
use App\Services\Interfaces\ShipServiceInterface;
use App\Services\Interfaces\ShotServiceInterface;

class GameController extends Controller
{
    /**
     * Render the Battle Ships game view
     *
     * @param GridServiceInterface $gridService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function battleShips(GridServiceInterface $gridService)
    {
        $grid = $gridService->createGrid();
        $grid = collect($grid);

        return view('games.battle-ships', compact('grid'));
    }

    /**
     * Shot to a grid cell
     *
     * @param ShotRequest $request
     * @param ShotServiceInterface $shotService
     * @param ShipServiceInterface $shipService
     * @param GameServiceInterface $gameService
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function shot(ShotRequest $request, ShotServiceInterface $shotService, ShipServiceInterface $shipService, GameServiceInterface $gameService)
    {
        $response = [
            'grid' => $shotService->shootCell($request->validated()),
        ];

        if (! $shipService->checkForSailingShips($response['grid'])) {
            $shotCount = $gameService->finishGame();

            $response['shot_count'] = $shotCount;
        }

        return response($response);
    }
}
