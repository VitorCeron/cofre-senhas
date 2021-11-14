export default httpClient => ({
    create: async ({ name, password, link, observation, user_id }) => {
        try {
            return await httpClient.post('/user_passwords/store', {
                name,
                password,
                link,
                observation,
                user_id
              });
        } catch (error) {
            return {error};
        }
    },
  })
  