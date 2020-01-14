<?php


namespace app\statistical\controller;


use cmf\controller\AdminBaseController;
use app\webapi\model\UserOrderModer;


class EquipmentListController extends AdminBaseController
{

    //打印部门整个的消费情况
    public function departmentOut($data)
    {

        //导出
        $path = dirname(__FILE__); //找到当前脚本所在路径
        vendor("PHPExcel.PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.IWriter");
        vendor("PHPExcel.PHPExcel.Writer.Abstract");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();
       // $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        // 实例化完了之后就先把数据库里面的数据查出来

        // 设置表头信息
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '部门编号')
            ->setCellValue('B1', '部门名称')
            ->setCellValue('C1', '剩余积分')
            ->setCellValue('D1', '共计消费的积分')
            ->setCellValue('E1', '购买模板数量');




        /*--------------开始从数据库提取信息插入Excel表中------------------*/

        $i=2;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($data);  //计算有多少条数据
        for ($i = 2; $i <= $count+1; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $data[$i-2]['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $data[$i-2]['dep_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $data[$i-2]['integral']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $data[$i-2]['by_integral']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $data[$i-2]['num']);
        }


        /*--------------下面是设置其他信息------------------*/

        $objPHPExcel->getActiveSheet()->setTitle('productaccess');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");

        header('Content-Disposition: attachment;filename="全体部门消费统计.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件

    }


    //打印单个部门的全部订单列表信息
    public function getDepartmentSelfOut($data)
    {
        //导出
        $path = dirname(__FILE__); //找到当前脚本所在路径
        vendor("PHPExcel.PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.IWriter");
        vendor("PHPExcel.PHPExcel.Writer.Abstract");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        // 实例化完了之后就先把数据库里面的数据查出来

        // 设置表头信息
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '订单编号')
            ->setCellValue('B1', '模板编号')
            ->setCellValue('C1', '购买人')
            ->setCellValue('D1', '部门')
            ->setCellValue('E1', '购买时间')
            ->setCellValue('F1', '价格')
            ->setCellValue('G1', '订单状态');




        /*--------------开始从数据库提取信息插入Excel表中------------------*/

        $i=2;  //定义一个i变量，目的是在循环输出数据是控制行数
        $count = count($data);  //计算有多少条数据
        for ($i = 2; $i <= $count+1; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $data[$i-2]['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $data[$i-2]['template_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $data[$i-2]['user_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $data[$i-2]['department_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $data[$i-2]['create_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $data[$i-2]['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, '完成');

        }


        /*--------------下面是设置其他信息------------------*/

        $objPHPExcel->getActiveSheet()->setTitle('productaccess');      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来

        $PHPWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel,"Excel2007");

        header('Content-Disposition: attachment;filename="部门详细.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
    }
}