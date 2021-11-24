export default httpClient => ({
    get: async () => {
        try {
            let response = await httpClient.get(`/dashboard/get`);
            return response.data;
        } catch (error) {
            return {error};
        }
    },
  })
  