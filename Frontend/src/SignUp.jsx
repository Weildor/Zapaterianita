import { useState } from 'react';
import './signUp.css'; 

function SignUp({ onSignUpSuccess, onGoToLogin }) {
  const [formData, setFormData] = useState({
    nombreCompleto: '',
    nombreUsuario: '',
    email: '',
    password: ''
  });

  const API_URL = import.meta.env.VITE_API_URL; // Verifica que sea http://localhost:8000/api

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await fetch(`${API_URL}/usuarios`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      const data = await res.json();

      if (data.response) {
        alert("¡Registro exitoso! Ya puedes entrar.");
        onSignUpSuccess();
      } else {
        alert(data.message || "Error al registrar");
      }
    } catch (error) {
      console.error("Error en el registro:", error);
      alert("Error de conexión. ¿El backend está encendido?");
    }
  };

  return (
    <div className="login-container">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2>Crear Cuenta</h2>
        <div className="input-group">
          <input type="text" placeholder="Nombre Completo" required 
            onChange={(e) => setFormData({...formData, nombreCompleto: e.target.value})} />
        </div>
        <div className="input-group">
          <input type="text" placeholder="Usuario" required 
            onChange={(e) => setFormData({...formData, nombreUsuario: e.target.value})} />
        </div>
        <div className="input-group">
          <input type="email" placeholder="Email" required 
            onChange={(e) => setFormData({...formData, email: e.target.value})} />
        </div>
        <div className="input-group">
          <input type="password" placeholder="Contraseña" required 
            onChange={(e) => setFormData({...formData, password: e.target.value})} />
        </div>
        <button type="submit" className="btn-login">Registrarme</button>
        <p className="form-footer">
          ¿Ya tienes cuenta? <span onClick={onGoToLogin} className="link-btn">Inicia sesión</span>
        </p>
      </form>
    </div>
  );
}

export default SignUp;