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
  })
  