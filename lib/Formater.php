<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 12:42:48
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 13:25:36
 */
namespace Playoffs\Lib;
use Illuminate\Support;  // https://laravel.com/docs/5.8/collections - provides the collect methods & collections class
use LSS\Array2Xml;

class Formater{

	/**
	 * format to xml
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function xml($data) {
		 header('Content-type: text/xml');
        
        // fix any keys starting with numbers
        $keyMap = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $xmlData = [];
        foreach ($data->all() as $row) {
            $xmlRow = [];
            foreach ($row as $key => $value) {
                $key = preg_replace_callback('(\d)', function($matches) use ($keyMap) {
                    return $keyMap[$matches[0]] . '_';
                }, $key);
                $xmlRow[$key] = $value;
            }
            $xmlData[] = $xmlRow;
        }
        $xml = Array2XML::createXML('data', [
            'entry' => $xmlData
        ]);
        return $xml->saveXML();
	}

	/**
	 * Format to json
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function json($data) {
		header('Content-type: application/json');
        return json_encode($data->all());
	}

	/**
	 * fornmat to csv
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function csv($data) {
		header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv";');
        if (!$data->count()) {
            return;
        }
        $csv = [];
        
        // extract headings
        // replace underscores with space & ucfirst each word for a decent headings
        $headings = collect($data->get(0))->keys();
        $headings = $headings->map(function($item, $key) {
            return collect(explode('_', $item))
                ->map(function($item, $key) {
                    return ucfirst($item);
                })
                ->join(' ');
        });
        $csv[] = $headings->join(',');

        // format data
        foreach ($data as $dataRow) {
            $csv[] = implode(',', array_values($dataRow));
        }
        return implode("\n", $csv);
	}

	/**
	 * format to html
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function html($data) {
		 if (!$data->count()) {
            return $this->htmlTemplate('Sorry, no matching data was found');
        }
        
        // extract headings
        // replace underscores with space & ucfirst each word for a decent heading
        $headings = collect($data->get(0))->keys();
        $headings = $headings->map(function($item, $key) {
            return collect(explode('_', $item))
                ->map(function($item, $key) {
                    return ucfirst($item);
                })
                ->join(' ');
        });
        $headings = '<tr><th>' . $headings->join('</th><th>') . '</th></tr>';

        // output data
        $rows = [];
        foreach ($data as $dataRow) {
            $row = '<tr>';
            foreach ($dataRow as $key => $value) {
                $row .= '<td>' . $value . '</td>';
            }
            $row .= '</tr>';
            $rows[] = $row;
        }
        $rows = implode('', $rows);

        return [
        	'header' => $headings,
        	'rows'	=> $rows
        ];
	}

}