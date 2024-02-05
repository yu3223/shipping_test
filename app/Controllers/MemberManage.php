<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MembersModel;

class MemberManage extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $memberData = session()->get("memberdata")->m_account;

        return $this->respond([
            "status" => true,
            "data"   => "Welcome member ".$memberData,
            "msg"    => "Welcome member ".$memberData
        ]);
    }

    public function renderEditMemberDataPage()
    {
        return $this->respond([
            "status" => true,
            "data"   => "Welcome editMemberData",
            "msg"    => "Welcome editMemberData"
        ]);
    }

    public function update()
    {
        //$data = $this->request->getJSON(true);
        $data = $this->request->getPost();

        $m_id = session()->get("memberdata")->m_id;

        $membersModel = new MembersModel();
        $verifyUserData = $membersModel->where("m_id", $m_id)->first();

        if($verifyUserData === null) {
            return $this->fail("查無此帳號", 404);
        }
        
        $password   = $data['password'] ?? null;
        $repassword = $data['repassword'] ?? null;

        if($password != $repassword) {
            return $this->fail("密碼驗證錯誤", 403);
        }else{
            $updateValues = [
                'm_password' =>  password_hash($password, PASSWORD_DEFAULT),
            ];
            $membersModel->update($verifyUserData['m_id'], $updateValues);
        }

        return $this->respond([
            "status" => true,
            "msg"    => "個人資料修改成功"
        ]);
    }

    public function delete()
    {
        $m_id = session()->get("memberdata")->m_id;

        $membersModel = new MembersModel();
        $verifyUserData = $membersModel->where("m_id", $m_id)->first();

        if($verifyUserData === null) {
            return $this->fail("查無此帳號", 404);
        }

        $membersModel->delete($verifyUserData['m_id']);

        return $this->respond([
            "status" => true,
            "msg"    => "帳號刪除成功"
        ]);
    }
}