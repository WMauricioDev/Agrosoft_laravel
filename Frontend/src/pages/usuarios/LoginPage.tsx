import React from 'react';
import { useAuth } from '../../context/AuthContext';
import { Navigate } from 'react-router-dom';
import { Box, Typography, Link, Divider, useTheme, useMediaQuery } from '@mui/material';
import { motion } from 'framer-motion';
import Login from '../../components/usuarios/Login';
import { Link as RouterLink } from 'react-router-dom';
import AgrosisLogotic from '../../assets/def_AGROSIS_LOGOTIC.png';
import LogoSena from '../../assets/logob.png';

const LoginPage: React.FC = () => {
  const { isAuthenticated } = useAuth();
  const theme = useTheme();
  const isSmallScreen = useMediaQuery(theme.breakpoints.down('sm'));

  if (isAuthenticated) {
    return <Navigate to="/" replace={true} />;
  }

  return (
    <Box
      sx={{
        minHeight: '100vh',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        position: 'relative',
        overflow: 'hidden',
        backgroundColor: '#27a35e', // Verde más oscuro
      }} 
    >
      {/* Fondo con línea horizontal curva (~) */}
      <Box
        sx={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          zIndex: 0,
        }}
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 1440 320"
          style={{
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
          }}
          preserveAspectRatio="none"
        >
          <path
            fill="#fff"
            fillOpacity="1"
            d="M0,0 L0,160 Q360,140 720,160 Q1080,180 1440,160 L1440,0 Z"
          />
        </svg>
      </Box>

      {/* Logo AGROSIS en la esquina superior izquierda */}
      <Box
        sx={{
          position: 'absolute',
          top: 16,
          left: 16,
          zIndex: 1,
        }}
      >
        <img
          src={AgrosisLogotic}
          alt="AGROSIS Logotic Small"
          style={{
            width: isSmallScreen ? '100px' : '140px',
            height: 'auto',
          }}
        />
      </Box>

      {/* Logo SENA en la esquina inferior izquierda */}
      <Box
        sx={{
          position: 'absolute',
          bottom: 16,
          left: 22,
          zIndex: 1,
        }}
      >
        <img
          src={LogoSena} 
          alt="Logo SENA"
          style={{
            width: isSmallScreen ? '700px' : '130px', 
            height: 'auto',
          }}
        />
      </Box>

      {/* Contenedor principal */}
      <Box
        sx={{
          width: { xs: '90%', sm: '70%', md: '50%' },
          maxWidth: '600px',
          backgroundColor: '#fff',
          borderRadius: '24px',
          boxShadow: '0 15px 40px rgba(0,0,0,0.1)',
          position: 'relative',
          zIndex: 2,
          overflow: 'hidden',
          p: { xs: 2, sm: 4 },
        }}
      >
        {/* Mitad izquierda: Formulario */}
        <Box
          sx={{
            flex: 1,
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'center',
            p: { xs: 3, sm: 4 },
          }}
        >
        <motion.div
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.8, ease: 'easeOut' }}
        >
          <Typography
            variant="h4"
            gutterBottom
            sx={{
              fontWeight: 'bold',
              color: '#1a202c',
              textAlign: 'center',
              mb: 1,
            }}
          >
              Iniciar Sesión
            </Typography>
            <Typography
              variant="subtitle1"
              sx={{
                color: '#718096',
                textAlign: 'center',
                mb: 3,
              }}
            >
              Ingresa tus credenciales para acceder
            </Typography>
            <Login />
            <Link
              component={RouterLink}
              to="/forgot-password"
              sx={{
                mt: 2,
                color: '#718096',
                textDecoration: 'none',
                display: 'block',
                textAlign: 'center',
                fontSize: '0.9rem',
                '&:hover': { color: '#27a35e' },
              }}
            >
              ¿Olvidaste tu contraseña?
            </Link>
            <Typography
              variant="body2"
              sx={{
                mt: 2,
                color: '#718096',
                textAlign: 'center',
                fontSize: '0.9rem',
              }}
            >
              ¿No estás registrado?{' '}
              <Link
                component={RouterLink}
                to="/register"
                sx={{ color: '#27a35e', textDecoration: 'none', '&:hover': { textDecoration: 'underline' } }}
              >
                Regístrate
              </Link>
            </Typography>
          </motion.div>
        </Box>

        {/* Línea divisora vertical: solo visible en md+ */}
        {!isSmallScreen && (
          <Divider
            orientation="vertical"
            flexItem
            sx={{
              borderColor: '#e2e8f0',
              borderWidth: '2px',
              borderStyle: 'solid',
              height: '80%',
              alignSelf: 'center',
            }}
          />
        )}
      </Box>
    </Box>
  );
};

export default LoginPage;
