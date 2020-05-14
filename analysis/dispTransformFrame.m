function [dprime] = dispTransformFrame(x_start, y_start, x_end, y_end, d)

    % x_start, y_start : coordinates of the start node
    % x_end, y_end : coordinates of the end node
    % L : length of member
    % R: rotation matrix
    % kprime: element stiffness matrix in local coordinate system
    % k: element stiffness matrix in global coordinate system

    L= sqrt((x_end-x_start)^2+(y_end-y_start)^2);  
    costetha= (x_end-x_start)/L;
    sintetha= (y_end-y_start)/L;
    R= [ costetha sintetha 0 0 0 0; 
        -sintetha costetha 0 0 0 0;
        0 0 1 0 0 0;
        0 0 0 costetha sintetha 0;
        0 0 0 -sintetha costetha 0;
        0 0 0 0 0 1];   
    dprime=R*d;
end



