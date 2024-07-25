import Http from "@/services/Http.js";


export const createPackage = async (formData) => {
    try {
        const response= await Http.post('package/create-package', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const getPackage = async (id) => {
    try {
        const response= await Http.get(`package/${id}`);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const getPackages = async () => {
    try {
        const response= await Http.get('packages');
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const updatePackage = async (id, formData) => {
    try {
        const response = await Http.patch(`/package/update-package/${id}`, formData);
        return response;
    } catch (error) {
        throw error;
    }
  };

export const deletePackage = async (id) => {
    try {
        const response=  Http.delete(`package/delete-package/${id}`);
        return response;
    } catch (error) {
        throw error;
    }
  };
