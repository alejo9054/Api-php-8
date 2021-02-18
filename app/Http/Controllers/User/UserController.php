<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //lista completa de usuarios disponibles en la base de datos
        $usuarios = User::all();

       /* return $usuarios; // funciona pero es mejor meter los datos en u elemento raiz(en esta caso será data)*/
       return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//store se hace con el metodo post
    {
        //agregando reglas de validacion
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
        //usamos el metodo validate para que valide las reglas dadas
        $this->validate($request,$rules);

        //crear instancias de usuarios
        $campos = $request->all();// campos igual a todo lo que venga con la peticion(nombre, email, etc...)
        //el password deve ser encriptada
        $campos['passwrd'] = bcrypt($request->password);
        //admin y verified son campos que solo puedemos poner nosotros no cualquier persna
        $campos['verified'] = User::USUSARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();//debemos generar el tokem de verificacion
        $campos['admin'] = User::USUARIO_REGULAR;
        

        //usamos la funsion creeate para crear una asignacion masiva, asisgnamos todos los atributos que son de esta instacia de usuario
        $usuario = User::create($campos);//lo hacemos por medio del array campos

        return $this->showOne($usuario,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)// aqui $user debe llamarse igual que la instancia, es decir, no podemos poner $usuario
    {
        //muestra un usuario especifico para un id especifico
        //$usuario = User::findOrFail($id);// ayuda a mostrar un error en caso de que el usuario no exista

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)//recordar que las acctualizaciones se hacen por el metodo put y por x-www-form-urlencoded// User $user agregando a inyeccion implicita del modelo
    {
        //$user = User::findOrFail($id);//guardamos la id del usuario en una variable
        // definimos las reglas
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,//se agrega tambn la exepcion de su propio email 
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];
        //usamos el metodo para validar las reglas
        $this->validate($request,$rules);

        //si edita el nombre, reemplazar
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        // si se pide actualizar el email y a la vez el email es diferente del actual
        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::USUSARIO_NO_VERIFICADO; //al cambiar el email debe verificarse de nuevo
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }
        // si solicita un cambio de contraseña
        if ($request->has('password')){
            $user->password = bcrypt($request->password);
        }
        //para modificar el admin(solo los usuarios administrador deberian poder usar esta accion)
        if ($request->has('admin')){
            //restriccion de que el usuario solo puede ser admin si el usuario es verificado
            if (!$user->esVerificado()) {
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador',409);
            }
            $user->admin = $request->admin;
        }
        // debemos verificar que de verdadse halla cambiado un valor para que se actualicen los datos
        if (!$user->isDirty()){
            return $this->errorResponse(['error'=> 'Se debe especificar al menos un valor diferente para actualizar'], 422);
        }
        //guardamos cambios y retornamos
        $user->save();

        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)// inyeccion del modelo
    {
        //
        //$user = User::findOrFail($id);
        $user->delete();
        return $this->showOne($user);
    }
}
