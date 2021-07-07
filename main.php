<?php
class AccessCounter extends WP_Widget{
    function __construct(){
        parent::__construct(
            'AccessCounter',
            'アクセスカウンター',
            array('description' => 'アクセスカウンターを表示するウィジェット')
        );
    }
  
    public function widget($args, $instance){
        echo $args['before_widget'];
        //ここから本体
        try {
            $dbh = new PDO(
                'mysql:host=localhost;dbname=okinotori;charset=utf8mb4',
                'okinotori',  //ユーザー名
                '',           //パスワード
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
            );
            date_default_timezone_set('Asia/Tokyo');
            $today = date("Y-m-d");
            $result = $dbh->prepare('SELECT SUM(count) FROM wp_cocoon_accesses WHERE date = ?');
            $result->execute([$today]);
            $result = $result->fetch();
            $todayCount = $result[0] ?? 0;		

            $result = $dbh->query('SELECT SUM(count) FROM wp_cocoon_accesses');
            $result = $result->fetch();
            $totalCount = $result[0];

            echo "<span class=\"fa fa-bar-chart fa-fw\"></span><span>本日:\t".$todayCount."\t全体:\t".$totalCount."</span>";
        } catch (PDOException $e) {
            exit($e->getMessage()); 
        }
        //ここまで本体
        echo $args['after_widget'];
    }
  }
  
add_action(
    'widgets_init',
    function(){
        register_widget('AccessCounter');
    }
);
?>