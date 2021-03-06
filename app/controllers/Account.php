<?php
class Account extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->load_model('User');
    }

    public function indexAction()
    {
        $this->view->render('account/login');
    }

    public function loginAction()
    {
        $validation = new Validate();
        if ($_POST) {
            $validation->check($_POST, [
                'email' => [
                    'display' => "Email",
                    'required' => true,
                    'valid_email' => true
                ],
                'password' => [
                    'display' => "Password",
                    'required' => true
                ]
            ]);
            if ($validation->passed()) {
                $user = $this->UserModel->findByEmail($_POST['email']);
                if ($user && password_verify(Input::get('password'), $user->password)) {
                    $remember = (isset($_POST['remember_me']) && $_POST['remember_me']) ? true : false;
                    $user->login($remember);
                    Router::redirect('');
                } else {
                    $validation->addError("There is an error with your username or password.");
                }
            }
        }
        $this->view->display_errors = $validation->display_errors();
        $this->view->render('account/login');
    }

    public function logoutAction()
    {
        if (currentUser()) {
            currentUser()->logout();
        }
        Router::redirect('account/login');
    }

    public function registerAction()
    {
        $this->view->render('account/register');
    }
}
