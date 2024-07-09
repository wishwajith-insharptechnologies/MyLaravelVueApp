import Http from "@/services/Http.js";


export const createProduct = async ( data ) => {
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

  export const updateProduct = async (productId, formData) => {
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

export const getProducts = async (currentPage, pageSize) => {
    try {
        const response = await Http.get(`projects?page=${currentPage}&per=${pageSize}`);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const deleteProject = async (id) => {
    try {
        const response = await Http.delete(`projects/delete/project/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const getProduct = async (productId) => {
    try {
        const response= await Http.get(`project/${productId}`);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const getProjectsList = async () => {
    try {
        const response = await Http.get('projects/list');
        return response.data.data;
    } catch (error) {
        throw error;
    }
};
