import qs from "qs";

export default httpClient => ({
    get: async ({ page, limit, filter }) => {
        let query = qs.stringify({ filter: filter }, { encode: false });
        try {
            let response = await httpClient.get(`/logs/get?${query}`, {
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
  })
  