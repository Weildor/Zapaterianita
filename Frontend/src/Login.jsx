import { useState, useEffect } from 'react';
import './login.css';

function Login({ onLoginSuccess, onGoToSignUp }) {
  const handleSubmit = (e) => {
    e.preventDefault();
    // Aquí podrías validar con tu API (backend)
    // Por ahora, simulamos que el login es exitoso:
    onLoginSuccess();
  };

  return (
    <div className="login-container">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Login</h2>
        <div className="input-group">
          <input type="text" placeholder="Usuario" required />
        </div>
        <div className="input-group">
          <input type="password" placeholder="Contraseña" required />
        </div>
        <button type="submit" className="btn-login">Entrar</button>
        <p className="form-footer">
  ¿No tienes cuenta? 
  <span onClick={onGoToSignUp} className="link-btn">Sign Up</span>
</p>
      </form>
    </div>
  );
}

export default Login;

/*
function Login({ onLoginSuccess }) {
  const handleSubmit = (e) => {
    e.preventDefault();
    // Aquí podrías validar con tu API (backend)
    // Por ahora, simulamos que el login es exitoso:
    onLoginSuccess();
  };

  return (
    <div className="login-container">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Login</h2>
        <div className="input-group">
          <input type="text" placeholder="Usuario" required />
        </div>
        <div className="input-group">
          <input type="password" placeholder="Contraseña" required />
        </div>
        <button type="submit" className="btn-login">Entrar</button>
        <button tyte="submit" className="btn-Sign-Up">Registrarse</button>
      </form>
    </div>
  );
}

export default Login;

/*
function Login({ onLoginSuccess, onGoToSignUp }) {
  // ... tu handle submit ...

  return (
    <div className="login-container">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Login</h2>
        {/* ... inputs ... }
        <button type="submit" className="btn-login">Entrar</button>
        
        {/* Cambiamos el botón para que navegue al SignUp }
        <button 
          type="button" 
          className="btn-Sign-Up" 
          onClick={onGoToSignUp}
        >
          Registrarse
        </button>
      </form>
    </div>
  );
}
*/
