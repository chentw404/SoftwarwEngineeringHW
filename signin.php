<?PHP
header("Content-Type: text/html; charset=utf8");
if (!isset($_POST["submit"])) {
    exit("无权限调用！");
} //检测是否有submit操作 
include('connect.php'); //链接数据库
$name = $_POST['name']; //post获得用户名表单值
$passowrd = $_POST['password']; //post获得用户密码单值

if ($name && $passowrd) { //如果用户名和密码都不为空
    $sql = "select * from users where username = '$name' and password='$passowrd'"; //检测数据库是否有对应的username和password的sql
    $result = mysqli_query($con, $sql); //执行sql
    $rows = mysqli_num_rows($result); //返回一个数值
    $row = mysqli_fetch_assoc($result);
    $userid=$row['id'];
    $usertype=$row['usertype'];
    //判断输入的用户名或者密码是否正确
    if ($rows > 0) {
        session_start();
        $_SESSION['usertype']=$usertype;
        $_SESSION['username'] = $name;
        $_SESSION['userid']=$userid;
        echo "<script>alert('登陆成功!')</script>";
        //判断usertype来确定为何种用户，定义学生用户usertype为1，教师用户为2，管理员用户为0
        if($_SESSION['usertype']==1)
        header("refresh:0;url=../welcome.php");
        else if($_SESSION['usertype']==2)
        header("refresh:0;url=../teacher_welcome.php");
        else if($_SESSION['usertype']==0)
        header("refresh:0;url=../admin.php");
    } else {
        echo "<script>alert('用户名或密码错误！')</script>";
        echo "
                    <script>
                            setTimeout(function(){window.location.href='../signinorup.php';},1000);
                    </script>
                ";
    }
} else { //如果用户名或密码有空
    echo "<script>alert('用户名或密码不能为空！')</script>";
    echo "
                      <script>
                            setTimeout(function(){window.location.href='../signinorup.php';},1000);
                      </script>";

    //如果错误使用js 1秒后跳转到登录页面重试;
}
mysqli_close($con);
