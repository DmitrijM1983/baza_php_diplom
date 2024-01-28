<?php

class User
{
    private DataBase $pdo;
    private string $sessionName;
    private bool $isLoggedIn = false;
    public array $data;
    public string $cookieName;

    public function __construct($user = null)
    {
        $this->pdo = DataBase::getConnect();
        $this->sessionName = Config::get('session.user_session');
        $this->cookieName = Config::get('cookie.cookie_name');

        if (!$user) {
            if (Session::exists($this->sessionName)) {
                $user = Session::get($this->sessionName);

                if ($this->find($user)) {
                    $this->isLoggedIn = true;
                }
            }
        } else {
            if ($this->find($user)) {
                $this->isLoggedIn = true;
            }
        }
    }

    /**
     * @param string $table
     * @param array $fields
     * @return void
     */
    public function create(string $table, array $fields): void
    {
        $this->pdo->insert($table, $fields);
    }

    /**
     * @param string|null $email
     * @param string|null $password
     * @param bool $remember
     * @return void
     */
    public function login(string $email = null, string $password = null, bool $remember = null): void
    {
        if (!$email && !$password && $this->exists()) {
            Session::put(Config::get('session.user_session'), $this->getData()->id);
            Redirect::to('users.php');
            exit();
        }
        $result = $this->find($email);
        if ($result) {
            if (password_verify($password, $this->getData()->password)) {
                Session::put(Config::get('session.user_session'), $this->getData()->id);

                if ($remember) {
                    $hash = hash('sha256', uniqid());
                    $checkHash = $this->pdo->get('user_cookie', ['user_id' => $this->getData()->id]);
                    if (!$checkHash) {
                        $this->pdo->insert('user_cookie', ['user_id' => $this->getData()->id, 'hash' => $hash]);
                    } else {
                        $hash = $checkHash[0]->hash;
                    }
                    Cookie::put(Config::get('cookie.cookie_name'), $hash, Config::get('cookie.cookie_expiry'));
                }

                if ($this->getData()->group_id === 2) {
                    Session::setFlash('success', "Привет, {$this->getData()->username}, you are is Admin!");
                } else {
                    Session::setFlash('success', "Привет, {$this->getData()->username}!");
                }
                Redirect::to('users.php');
                exit();
            }
        }
        Session::setFlash('error', 'Email or password is incorrect');
        Redirect::to('page_login.php');
        exit();
    }

    /**
     * @param string $value
     * @return bool
     */
    public function find(string $value): bool
    {
        if (is_numeric($value)) {
            $this->data = $this->pdo->get('users', ['id' => $value]);
            return true;
        } else {
            $this->data = $this->pdo->get('users', ['email' => $value]);
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data[0];
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->pdo->delete('user_cookie', ['user_id' => $this->getData()->id]);
        Session::delete($this->sessionName);
        Cookie::delete($this->cookieName);
    }

    /**
     * @return bool
     */
    public static function exists(): bool
    {
        return isset($_SESSION);
    }

    /**
     * @param $params
     * @param $id
     * @return void
     */
    public function update($params, $id = null): void
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->getData()->id;
        }
        $this->pdo->update('users', $params, $id);
    }

    /**
     * @return array|bool|object
     */
    public function getUsers(): object|bool|array
    {
        return $this->pdo->selectAll('users');
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        $roleValue = json_decode($this->pdo->get('groups',
            ['id' => $this->getData()->group_id])[0]->permissions, true);
        if (is_array($roleValue)) {
            if (array_key_first($roleValue) === 'admin') {
                return true;
            }
        }
        return false;
    }
}