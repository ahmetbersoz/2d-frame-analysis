function [kprime,k] = stiffTransformFrame(x_start, y_start, x_end, y_end, A, I ,E)

    % x_start, y_start : coordinates of the start node
    % x_end, y_end : coordinates of the end node
    % L : length of member
    % R: rotation matrix
    % R_tr: transpose of rotation matrix
    % kprime: element stiffness matrix in local coordinate system
    % k: element stiffness matrix in global coordinate system
    % E: elastic modulus
    % I: moment of intertia
    % A: member area   
    
    L= sqrt((x_end-x_start)^2+(y_end-y_start)^2);  
    costetha= (x_end-x_start)/L;
    sintetha= (y_end-y_start)/L;
    R= [ costetha sintetha 0 0 0 0; 
        -sintetha costetha 0 0 0 0;
        0 0 1 0 0 0;
        0 0 0 costetha sintetha 0;
        0 0 0 -sintetha costetha 0;
        0 0 0 0 0 1];
   
    R_tr = transpose(R);    
    
    kprime = [ E*A/L 0 0 -E*A/L 0 0; 
        0 12*E*I/L^3 6*E*I/L^2 0 -12*E*I/L^3 6*E*I/L^2; 
        0 6*E*I/L^2 4*E*I/L 0 -6*E*I/L^2 2*E*I/L;
        -E*A/L 0 0 E*A/L 0 0;
        0 -12*E*I/L^3 -6*E*I/L^2 0 12*E*I/L^3 -6*E*I/L^2;
        0 6*E*I/L^2 2*E*I/L 0 -6*E*I/L^2 4*E*I/L ];
    
    k= R_tr*kprime*R;
end



