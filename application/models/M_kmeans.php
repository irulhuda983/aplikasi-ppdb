<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kmeans extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function kmeans_2($data_ppdb) {
        ///ambil data ppdb
        $dataArea   = [];

        // init data mining
        $dataMining = [];

        // normalisasi data
        foreach($data_ppdb as $item) {
            array_push($dataArea, [
                'id_kecamatan' => $item->kecamatan_id,
                'kecamatan' => $item->nama,
                'jumlah' => $item->jumlah,
                'tahun' => $item->tahun,
            ]);
        }

        // sort data
        usort($dataArea, function($a, $b) {
            return $a['jumlah'] < $b['jumlah'];
        });

        // tentukan centroid
        $centroid = $this->db->get('tbl_centroid')->result();
        $ctr1 = 328;
        $ctr2 = 20;
        $ctr3 = 1;

        if(count($centroid) > 0) {
            $ctr1 = $centroid[0]->center1;
            $ctr2 = $centroid[0]->center2;
            $ctr3 = $centroid[0]->center3;
        }
        // end menentukan centroid

        // generate iterasi
        $i = 0;
        $tempCtr1 = $ctr1;
        $tempCtr2 = $ctr2;
        $tempCtr3 = $ctr3;
        $oldData = $dataArea;
        $newData = [];
        while(true){
            if($i != 0) {
                $iteration = $this->iteration($i, $oldData, $tempCtr1, $tempCtr2, $tempCtr3);
                $oldData = $newData;
                $newData = $iteration['data'];
                $tempCtr1 = $iteration['new_centroid']['ctr1'];
                $tempCtr2 = $iteration['new_centroid']['ctr2'];
                $tempCtr3 = $iteration['new_centroid']['ctr3'];

                array_push($dataMining, [
                    'nama' => $iteration['nama'],
                    'data' => $iteration['data'],
                ]);

                $cek = $this->perubahanClass($oldData, $newData);

                if(!$cek) {
                    break;
                }
            }else{
                $iteration = $this->iteration($i, $oldData, $tempCtr1, $tempCtr2, $tempCtr3);
                $oldData = $iteration['data'];
                $newData = $iteration['data'];
                $tempCtr1 = $iteration['new_centroid']['ctr1'];
                $tempCtr2 = $iteration['new_centroid']['ctr2'];
                $tempCtr3 = $iteration['new_centroid']['ctr3'];

                array_push($dataMining, [
                    'nama' => $iteration['nama'],
                    'data' => $iteration['data'],
                ]);
            }

            $i++;
        }

        $lastIteration = $dataMining[count($dataMining) - 1];


        $arrData = [
            'data_kumpul' => $dataArea,
            'data_mining' => $dataMining,
            'cluster' => $this->getHasilCluster($lastIteration['data']),
        ];

        return $arrData;
        // return json_encode($arrData);
    }

    public function iteration($number, $data, $ctr1, $ctr2, $ctr3)
    {
        $arrData = [];

        $dataC1 = [];
        $dataC2 = [];
        $dataC3 = [];

        foreach($data as $key => $value) {
            $jumlah = (int) $value['jumlah'];
            $jarak = $this->getJarak($jumlah, $ctr1, $ctr2, $ctr3);
            $cluster = $this->getClass($jarak);

            switch ($cluster) {
                    case 1:
                    array_push($dataC1, $jumlah);
                    break;
                case 2:
                    array_push($dataC2, $jumlah);
                    break;
                case 3:
                    array_push($dataC3, $jumlah);
                    break;
            }

            array_push($arrData, [
                'no' => $key + 1,
                'id_kecamatan' => $value['id_kecamatan'],
                'kecamatan' => $value['kecamatan'],
                'jumlah' => $jumlah,
                'tahun' => $value['tahun'],
                'minimun' => min($jarak),
                'cluster' => $cluster,
                'DC1' => $jarak['dc1'],
                'DC2' => $jarak['dc2'],
                'DC3' => $jarak['dc3'],
                'C1' => $cluster == 1 ? $jumlah : null,
                'C2' => $cluster == 2 ? $jumlah : null,
                'C3' => $cluster == 3 ? $jumlah : null,
            ]);
        }

        $centroid = [
            'ctr1' => array_sum($dataC1) / count($dataC1),
            'ctr2' => array_sum($dataC2) / count($dataC2),
            'ctr3' => array_sum($dataC3) / count($dataC3),
        ];

        return [
            'nama' => 'Iterasi '. ($number + 1),
            'data' => $arrData,
            'new_centroid' => $centroid
        ];
    }

    public function getJarak($jumlah, $ctr1, $ctr2, $ctr3)
    {
        $dc1 = sqrt( pow( ($jumlah - $ctr1), 2) );
        $dc2 = sqrt( pow( ($jumlah - $ctr2), 2) );
        $dc3 = sqrt( pow( ($jumlah - $ctr3), 2) );

        return [
            'dc1' => $dc1,
            'dc2' => $dc2,
            'dc3' => $dc3,
        ];
    }

    public function getClass($arr)
    {
        $dc1 = $arr['dc1'];
        $dc2 = $arr['dc2'];
        $dc3 = $arr['dc3'];
        $min = min($arr);

        if($dc1 == $min) {
            return 1;
        }

        if($dc2 == $min) {
            return 2;
        }

        if($dc3 == $min) {
            return 3;
        }
    }

    public function perubahanClass($oldData, $newData) {
        $tmpArray = [];

        foreach($oldData as $key => $value) {
            if($oldData[$key]['cluster'] == $newData[$key]['cluster']) {
                array_push($tmpArray, 0);
            }else{
                array_push($tmpArray, 1);
            }
        }

        if(array_sum($tmpArray) > 0) {
            return true;
        }

        return false;
    }

    public function getHasilCluster($data)
    {
        $c1 = [];
        $c2 = [];
        $c3 = [];
        foreach($data as $item) {
            switch ($item['cluster']) {
                case 1:
                array_push($c1, $item);
                break;
            case 2:
                array_push($c2, $item);
                break;
            case 3:
                array_push($c3, $item);
                break;
            }
        }
        return [
            'c1' => $c1,
            'c2' => $c2,
            'c3' => $c3,
        ];
    }

}