import Http from "@/services/Http.js";


export const createProject = async ( data ) => {
    try {
        const headers = {
            'Content-Type': 'multipart/form-data',
          };
      const response = await Http.post("projects/create-project", data, {headers});
      return response.data;
    } catch (error) {
      throw error;
    }
  };

  export const updateProject = async (productId, formData) => {
    try {
        const headers = {
            'Content-Type': 'multipart/form-data',
        };

        const response = await Http.post(`projects/update-project/${productId}`, formData, { headers });
        return response.data;
    } catch (error) {
        throw error;
    }
};
