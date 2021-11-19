export default httpClient => ({
    register: async ({ name, email, password, password_confirmation }) => {
        try {
            return await httpClient.post('/auth/register', {
                name,
                email,
                password,
                password_confirmation
              });
        } catch (error) {
            return {error};
        }
    },

    login: async ({ email, password }) => {
        try {
            let response = await httpClient.post('/auth/login', {
                email,
                password
              });
              return response.data;
        } catch (error) {
            return {error};
        }
    },

    logout: async () => {
        try {
            let response = await httpClient.post('/auth/logout');
              return response.data;
        } catch (error) {
            return {error};
        }
    },
  })
  