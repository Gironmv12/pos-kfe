import api from './axios';

export const login = async (email, password) => {
    return api.post('/login', {
        email,
        password
    });
}