import Http from "@/services/Http.js";

// Fetch all roles
export const getAllRoles = async () => {
  try {
    const response = await Http.get('roles');
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Create a new role
export const createRole = async (roleData) => {
  try {
    const response = await Http.post('roles', roleData);
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Get a specific role by ID
export const getRoleById = async (roleId) => {
  try {
    const response = await Http.get(`roles/${roleId}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Update a role by ID
export const updateRole = async (roleId, roleData) => {
  try {
    const response = await Http.put(`roles/${roleId}`, roleData);
    return response.data;
  } catch (error) {
    throw error;
  }
};

// Delete a role by ID
export const deleteRole = async (roleId) => {
  try {
    await Http.delete(`roles/${roleId}`);
    return true; // Or return success message as needed
  } catch (error) {
    throw error;
  }
};
