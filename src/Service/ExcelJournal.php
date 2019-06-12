<?php


namespace App\Service;


use App\Entity\JournalDateMark;
use App\Entity\JournalMark;
use App\Entity\JournalSubject;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Validator\ValidatorBuilder;
use Yectep\PhpSpreadsheetBundle\Factory;

class ExcelJournal
{
    private $manager;
    public $sheet;
    public $spreadsheet;
    public $factory;
    public $countSheet;


    public function __construct($manager)
    {
        $this->manager = $manager;
        $this->factory = new Factory();
        $this->spreadsheet =  $this->factory->createSpreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function getSubjectJournal(int $group_id,int $subject_id){
        $subject =  $this->manager->getRepository(JournalSubject::class)
            ->getSubjectByAlis('ps-1501','fizra');

        $dates =  $this->manager->getRepository(JournalDateMark::class)
            ->getOnByPage($subject->getId());

        foreach ($subject->getStudents() as $student) {
            $students[] = $this->manager->getRepository(JournalMark::class)
                ->getOnMarksByStudent($student,$subject->getId());
        }

        $rows = 1;$row = 0;
        $this->createHeadTable($rows,$row,count($dates));

        $i = 3;
        foreach ($dates as $date) {// date
            $colString = Coordinate::stringFromColumnIndex($i);
            if($date->getDate()){
                $d = $date->getDate()->format('m d');
            }else{ $d ='';}
            $this->sheet->setCellValueByColumnAndRow($i, 2/*Рядок*/,$d);
            $this->sheet->mergeCells($colString . (2) . ":" . $colString . (3));
            $this->sheet->getColumnDimension($colString)->setWidth(3);
            $this->sheet->getRowDimension(3)->setRowHeight(20);
            $this->sheet->getStyle($colString . (2) . ":" . $colString . (3))->getAlignment()->setWrapText(true);
            $i++;
        }
        $i=1;
        foreach ($students as $student){
            $this->sheet->setCellValueByColumnAndRow(1, $i+$row/*Рядок*/, $i);
            $this->sheet->setCellValueByColumnAndRow(2, $i+$row/*Рядок*/, $student['studentName']);
            $j=3;
            foreach ($student['mark'] as $mark){
                $this->sheet->setCellValueByColumnAndRow($j,$i+3, $mark->getMark());
                $this->c_setHorizontal($this->sheet, $i+$row);
                $j++;
            }
            ++$i;
            $this->sheet->getRowDimension($i+2)->setRowHeight(20);
        }
        return $this->factory->createStreamedResponse($this->spreadsheet, 'Xls');
    }

    function createHeadTable(&$rows,&$row,$countDate){
        $this->sheet->setCellValue("A".$rows, ('id'));
        $row = ($rows==1)?$rows+2:$rows+1;
        $this->sheet->mergeCells('A'.$rows.':A'.$row);//Объяденение ячеек //HORIZONTAL_LEFT
        $this->c_setHorizontal($this->sheet, 'A'.$rows);
        $this->c_setVertical($this->sheet, 'A'.$rows);
        $this->sheet->getColumnDimension('A')->setWidth(3); //Ширина ячейки
        $this->sheet->getStyle("A".$rows)->getFont()->setBold(true);    //Шрифт жирным

        $this->sheet->setCellValue("B".$rows, ('Прізвище та ініціали'));
        $row = ($rows==1)?$rows+2:$rows+1;
        $this->sheet->mergeCells('B'.$rows.':B'.$row);//Объединение  ячейки
        $this->c_setHorizontal($this->sheet, 'B'.$rows);
        $this->c_setVertical($this->sheet, 'B'.$rows);
        $this->sheet->getColumnDimension('B')->setAutoSize(true);
        $this->sheet->getStyle("B".$rows)->getFont()->setBold(true);

        $colString = Coordinate::stringFromColumnIndex($countDate);

        $this->sheet->setCellValue("C1", ('Місяць, число'));
        $this->sheet->mergeCells('C1'.':'.$colString.'1');//Объединение  ячейки
        $this->c_setHorizontal($this->sheet, 'C'.$rows);
        $this->c_setVertical($this->sheet, 'C'.$rows);
        $this->sheet->getStyle("C".$rows)->getFont()->setBold(true);
    }

    function c_setHorizontal($sheet, $coordinates)
    {
        $sheet->getStyle($coordinates)->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );
    }

    function c_setVertical($sheet, $coordinates)
    {
        $sheet->getStyle($coordinates)->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );
    }
}