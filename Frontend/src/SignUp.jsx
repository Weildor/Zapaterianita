import './signUp.css'; // Reutilizamos los estilos de tarjeta

function SignUp({ onSignUpSuccess, onGoToLogin }) {
  const handleSubmit = (e) => {
    e.preventDefault();
    // Aquí harías el fetch POST a tu API de registro
    alert("Usuario registrado con éxito");
    onSignUpSuccess(); // Te manda al Login para que entres
  };

  return (
    <div className="login-container">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Sign Up</h2>
        <div className="input-group">
          <input type="text" placeholder="Nombre Completo" required />
        </div>
        <div className="input-group">
          <input type="text" placeholder="Usuario" required />
        </div>
        <div className="input-group">
          <input type="password" placeholder="Contraseña" required />
        </div>
        <button type="submit" className="btn-login">Registrarme</button>
        <p className="form-footer">
  ¿Ya tienes cuenta? 
  <span onClick={onGoToLogin} className="link-btn">
    Inicia sesión
  </span>
</p>
      </form>
    </div>
  );
}

export default SignUp;