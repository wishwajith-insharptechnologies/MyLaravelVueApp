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

export const getPackages = async (popToast) => {
    try {
        const response= await Http.get('packages');
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const editPackage = async (id, formData) => {
    try {
        const response = await Http.patch(`packages/update-package/${id}`, formData);
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
