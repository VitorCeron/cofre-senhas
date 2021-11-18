import qs from "qs";

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

    get: async ({ page, limit, filter }) => {
        let query = qs.stringify({ filter: filter }, { encode: false });
        try {
            let response = await httpClient.get(`/user_passwords/get?${query}`, {
                params: {
                    page, 
                    limit
                }
            });
            return response.data;
        } catch (error) {
            return {error};
        }
    },

    getById: async ({ passwordId }) => {
        try {
            let response = await httpClient.get(`/user_passwords/show/${passwordId}`);
            return response.data;
        } catch (error) {
            return {error};
        }
    },

    update: async ({ id, name, password, link, observation, user_id }) => {
        try {
            return await httpClient.put(`/user_passwords/update/${id}`, {
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

    delete: async ({ id }) => {
        try {
            return await httpClient.delete(`/user_passwords/destroy/${id}`);
        } catch (error) {
            return {error};
        }
    },
  })
  