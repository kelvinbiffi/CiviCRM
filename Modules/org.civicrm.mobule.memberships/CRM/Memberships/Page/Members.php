<?php

require_once 'CRM/Core/Page.php';

class CRM_Memberships_Page_Members extends CRM_Core_Page {

  private $strStartDate = "";
  private $strEndDate = "";
  private $strMessage = "";
  private $arrMemberships = array();

  private function testDate($testdate) {
    $pattern = "/^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/";
    if(preg_match($pattern,$testdate))
      return true;
    else
      return false;
  }

  private function formatDate($date){
    if (!$this->testDate($date)) {
      $this->strMessage = "The dates must be in the MM/DD/YYYY format";
      $date = "";
    }else{
      $arrDate = explode('/',$date);
      $date = $arrDate[2]."-".$arrDate[0]."-".$arrDate[1];
    }
    return $date;
  }

  private function setProperties(){
    if(isset($_REQUEST["action"]) && trim($_REQUEST["action"]) == "Search"){

      $this->strStartDate = (isset($_REQUEST["strStartDate"])
      && trim($_REQUEST["strStartDate"]) != "" ? $_REQUEST["strStartDate"] : "");

      $this->strEndDate = (isset($_REQUEST["strEndDate"])
      && trim($_REQUEST["strEndDate"]) != "" ? $_REQUEST["strEndDate"] : "");
    };
  }

  private function assingProperties(){
    $this->assign('strStartDate', $this->strStartDate);
    $this->assign('strEndDate', $this->strEndDate);
    $this->assign('strMessage', '<span class="error"><b>' . $this->strMessage . '</b></span>');
    $this->assign('arrMemberships', $this->arrMemberships);
    $this->assign('currentTime', date('Y-m-d H:i:s'));
  }

  private function makeReport(){

    $joinDate = "";
    $startDt = ($this->strStartDate ? $this->formatDate($this->strStartDate): "");
    $endDt = ($this->strEndDate ? $this->formatDate($this->strEndDate): "");
    if($this->strMessage == ""){
      if($startDt != "" && $endDt != ""){
        $start=strtotime($startDt);
        $end=strtotime($endDt);
        if($start > $end){
          $this->strMessage = "Start date must be less than end date";
        }else{
          $joinDate = " AND m.start_date >= '".$startDt."' AND m.end_date <= '".$endDt."' ";
        }
      }else if($startDt != ""){
        $joinDate = " AND m.start_date >= '".$startDt."' ";
      }else if($endDt != ""){
        $joinDate = " AND m.end_date <= '".$endDt."' ";
      }
    }

    $strQuery = "SELECT c.id as id,
                c.display_name as name,
                m.source as source,
                c.contact_type as type,
                m.start_date as start_date,
                m.end_date as end_date
                FROM civicrm_membership as m,
                civicrm_contact as c
                where m.contact_id = c.id ".
                $joinDate .
                "order by c.display_name asc;";

    $dao = CRM_Core_DAO::executeQuery($strQuery);

    //To increment array with query return
    $id = 0;
    while ($dao->fetch()) {
      $this->arrMemberships[$id]["id"] = $dao->id;
      $this->arrMemberships[$id]["name"] = $dao->name;
      $this->arrMemberships[$id]["source"] = $dao->source;
      $this->arrMemberships[$id]["type"] = $dao->type;
      $this->arrMemberships[$id]["start_date"] = $dao->start_date;
      $this->arrMemberships[$id]["end_date"] = $dao->end_date;
      $id++;
    }
  }

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('Memberships'));

    $this->setProperties();
    $this->makeReport();
    $this->assingProperties();

    parent::run();
  }
}
