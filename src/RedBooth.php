<?php 

class Redbooth {

    private $redbooth_username = 'TODO';
    private $redbooth_password = 'TODO';

    private $redbooth_projectid = 'TODO';
    private $redbooth_tasklistid = 'TODO';

    private $baseurl = 'https://redbooth.com/api/2/';

    public function createTask($title, $description)
    {
        $comment= array(
                        'body' => $description
        );
    
        $args = array(
                        'name' => $title,
                        'project_id' => $this->redbooth_projectid,
                        'task_list_id' => $this->redbooth_tasklistid,
                        'comments_attributes' => array($comment)
        );
    
        $projectid=$this->redbooth_projectid;
        $tasklistid=$this->redbooth_tasklistid;
    
        $data = $this->connectionHelper('tasks', $args, 'POST');
    
        return $data;
    }
    
    public function createComment($taskid, $comment)
    {
        $args = array(
                        'comments_attributes' =>
                        array(
                                        array(
                                                        'body' => $comment,
                                        )
                        )
        );
    
        $data = $this->connectionHelper('projects/' . $this->redbooth_projectid . '/tasks/' . $taskid , $args, 'PUT');
    
        return $data;
    }
    
    private function connectionHelper($urlpart, $args, $requestType)
    {
        $args = json_encode($args);
        $url = $this->baseurl . $urlpart;
        $ch = curl_init();

        switch($requestType)
        {
            case 'POST':
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            default:
                echo "not supported";
        }

        $username= $this->redbooth_username;
        $password= $this->redbooth_password;

        curl_setopt($ch, CURLOPT_POSTFIELDS, $args );

        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $mydata = curl_exec ($ch);

        if (curl_errno($ch))
        {
            $err_str = 'Failed to retrieve url [' . curl_error($ch) . ']' . "\n";

            echo $err_str;

            return false;
        }
        return $mydata;
    }

}

?>