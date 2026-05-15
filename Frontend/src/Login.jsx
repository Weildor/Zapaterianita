import { useState } from 'react';
import './login.css';

function Login({ onLoginSuccess, onGoToSignUp }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  // Obtenemos la URL base del .env (VITE_API_URL=http://localhost:8000/api)
  const API_URL = import.meta.env.VITE_API_URL;

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(''); 

    try {
      const res = await fetch(`${API_URL}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });

      const data = await res.json();

      if (data.response) {
        // 1. Guardamos el token que generó tu UsuarioModel.php
        localStorage.setItem('token_zapateria', data.result.token);
        
        // 2. Avisamos al App.jsx que el login fue exitoso
        onLoginSuccess();
      } else {
        // Mostramos el mensaje de "Correo o contraseña incorrectos" que viene del backend
        setError(data.message);
      }
    } catch (err) {
      setError("Error de conexión: ¿El backend está encendido?");
    }
  };

  return (
    <div className="login-container">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Iniciar Sesión</h2>
        
        {error && <p style={{ color: 'red', fontSize: '12px' }}>{error}</p>}

        <div className="input-group">
          <input 
            type="email" 
            placeholder="Usuario (Email)" 
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required 
          />
        </div>
        <div className="input-group">
          <input 
            type="password" 
            placeholder="Contraseña" 
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required 
          />
        </div>
        <button type="submit" className="btn-login">Entrar</button>
        
        <p className="form-footer">
          ¿No tienes cuenta? 
          <span onClick={onGoToSignUp} className="link-btn">Regístrate</span>
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
