<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 12:28:56
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 13:14:20
 * 
 */

namespace Playoffs\Model;
use Playoffs\Model;

class Export_model extends Model {
    private $filter = [];

	/**
	 * just to get the allowable filter
	 * @return [type] [description]
	 */
	public function filter($args) {
		$searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
		$this->filter =  $args->filter(function($value, $key) use ($searchArgs) {
                return in_array($key, $searchArgs);
            });
        return $this;
	}
	
    /**
     * taken from exporter.php
     * @return [type] [description]
     */
	public function getPlayerStats() {
        $search = $this->filter;

        $where = [];
        if ($search->has('playerId')) $where[] = "roster.id = '" . $search['playerId'] . "'";
        if ($search->has('player')) $where[] = "roster.name = '" . $search['player'] . "'";
        if ($search->has('team')) $where[] = "roster.team_code = '" . $search['team']. "'";
        if ($search->has('position')) $where[] = "roster.pos = '" . $search['position'] . "'";
        if ($search->has('country')) $where[] = "roster.nationality = '" . $search['country'] . "'";
        $where = implode(' AND ', $where);
        $sql = "
            SELECT roster.name, player_totals.*
            FROM player_totals
                INNER JOIN roster ON (roster.id = player_totals.player_id)
            WHERE $where";
        $data = $this->query($sql) ?: [];

        // calculate totals
        foreach ($data as &$row) {
            unset($row['player_id']);
            $row['total_points'] = ($row['3pt'] * 3) + ($row['2pt'] * 2) + $row['free_throws'];
            $row['field_goals_pct'] = $row['field_goals_attempted'] ? (round($row['field_goals'] / $row['field_goals_attempted'], 2) * 100) . '%' : 0;
            $row['3pt_pct'] = $row['3pt_attempted'] ? (round($row['3pt'] / $row['3pt_attempted'], 2) * 100) . '%' : 0;
            $row['2pt_pct'] = $row['2pt_attempted'] ? (round($row['2pt'] / $row['2pt_attempted'], 2) * 100) . '%' : 0;
            $row['free_throws_pct'] = $row['free_throws_attempted'] ? (round($row['free_throws'] / $row['free_throws_attempted'], 2) * 100) . '%' : 0;
            $row['total_rebounds'] = $row['offensive_rebounds'] + $row['defensive_rebounds'];
        }
        return collect($data);
    }

     /**
     * taken from exporter.php
     * @return [type] [description]
     */
    public  function getPlayers() {
        $search = $this->filter;

        $where = [];
        if ($search->has('playerId')) $where[] = "roster.id = '" . $search['playerId'] . "'";
        if ($search->has('player')) $where[] = "roster.name = '" . $search['player'] . "'";
        if ($search->has('team')) $where[] = "roster.team_code = '" . $search['team']. "'";
        if ($search->has('position')) $where[] = "roster.position = '" . $search['position'] . "'";
        if ($search->has('country')) $where[] = "roster.nationality = '" . $search['country'] . "'";
        $where = implode(' AND ', $where);
        $sql = "
            SELECT roster.*
            FROM roster
            WHERE $where";
        return collect($this->query($sql))
            ->map(function($item, $key) {
                unset($item['id']);
                return $item;
            });
    }


}