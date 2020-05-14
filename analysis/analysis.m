%v1.0

clc
clear all

%%%%% INPUT PHASE

%nodal point coordinates
%[X coordinate, Y coordinate]
XY = csvread('inputs/XY.csv',0,1);
%number of nodal points
NumNode = size(XY,1); 

%material properties
%[Area, Moment of Inertia, Young Modulus]
M = csvread('inputs/M.csv',0,1);

%element type and connectivity
%[Start Node, End Node, Material Property]
C = csvread('inputs/C.csv',0,1);
%number of elements
NumElem = size(C,1); 

%boundary conditions
%[Nodel point ID, translation in global x direction, translation in global y direction, rotation about global Z axis]
% 0 for free, 1 fore restranined state
S = csvread('inputs/S.csv');
%number of support points
NumSupport = size(S,1); 

%applied nodal point loads
%[nodal point ID, load component in global X,load component in global y, moment component about global Z]
L = csvread('inputs/L.csv');
%number of nodal points where load are applied
NumLoadJoint = size(L,1); 

%%%%% EQUATION NUMBERING
E=zeros(NumNode,3);
Dsorted=zeros(NumNode,3);
for i=1:size(S,1) %number of rows of E
    for j=1:3
        E(S(i,1),j)=S(i,j+1);
        Dsorted(S(i,1),j)=S(i,j+1);        
    end    
end

Dsorted

k=1;
for i=1:NumNode
    for j=1:3
        if(E(i,j)==0)
            E(i,j)=k;
            k=k+1;
        else
            E(i,j)=0;
        end        
    end
end
%number of equation
NumEq=k-1;
E
%%%%% GLOBAL STIFFNESS MATRIX [K]
K=zeros(NumEq,NumEq);

% k= element stiffnes matrix in global coordinates
% kprime= element stiffnes matrix in local coordinates
G=zeros(6,1);
for i=1:NumElem
    [kprime, k] = stiffTransformFrame(XY(C(i,1),1),XY(C(i,1),2), XY(C(i,2),1), XY(C(i,2),2), M(C(i,3),1), M(C(i,3),2), M(C(i,3),3));
    startNode= C(i,1);
    endNode= C(i,2);
    for j=1:3
        G(j)= E(startNode,j);
        G(j+3)= E(endNode,j);
    end
    for p=1:6
        for q=1:6
            P=G(p);
            Q=G(q);
            if(P~=0 && Q~=0) 
                K(P,Q)=K(P,Q)+k(p,q);
            end
        end
    end
end

K %global stiffness matrix

%%%%% GLOBAL LOAD VECTOR
F=zeros(NumEq,1);
for i=1:NumLoadJoint
    N=L(i,1);
    for q=1:3
        Q=E(N,q);
        if(Q~=0) 
            F(Q)=F(Q)+L(i,q+1);
        end
    end
end

F %global load vector

%%%%% STRUCTRURAL DISPLACEMENTS
D=gaussSolver(K,F);

D %structural displacements

%%%%% MEMBER END FORCES
% dGlobal= vector of element end displacements in global coordinates
% dLocal= vector of element end displacements in local coordinates
G=zeros(6,1);
dGlobal=zeros(6,NumElem);
fLocal=zeros(6,NumElem);
fGlobal=zeros(6,NumElem);
for i=1:NumElem
    dGlobal1=zeros(6,1);
    startNode= C(i,1);
    endNode= C(i,2);
    for j=1:3
        G(j)= E(startNode,j);
        G(j+3)= E(endNode,j);
    end
    for p=1:6
        if(G(p)~=0)
            dGlobal1(p)=D(G(p));
            dGlobal(p,i)=D(G(p));            
        end
    end    
    [dLocal1] = dispTransformFrame(XY(C(i,1),1),XY(C(i,1),2), XY(C(i,2),1), XY(C(i,2),2), dGlobal1); 
    [kLocal, kGlobal] = stiffTransformFrame(XY(C(i,1),1),XY(C(i,1),2), XY(C(i,2),1), XY(C(i,2),2), M(C(i,3),1), M(C(i,3),2), M(C(i,3),3));
    fLocal1=kLocal*dLocal1; 
    fGlobal1=kGlobal*dGlobal1; 
    for j=1:6
        fLocal(j,i)=fLocal1(j);
        fGlobal(j,i)=fGlobal1(j);
    end   
end

Rs=zeros(NumSupport,4);
%support reactions
for i=1:NumSupport
    Rs(i,1)=S(i,1);
    for j=1:NumElem
        startNode= C(j,1);
        endNode= C(j,2);
        for k=1:3
            if(startNode==S(i,1))
                Rs(i,k+1)=Rs(i,k+1)+fGlobal(k,j);
            end
            if(endNode==S(i,1))
                Rs(i,k+1)=Rs(i,k+1)+fGlobal(k+3,j);
            end
        end
    end
end

Dsorted


k=1;
for i=1:NumNode
    for j=1:3
        if(Dsorted(i,j)==0)
            Dsorted(i,j)=D(k);
            k=k+1;
        else 
            Dsorted(i,j)=0;
        end
    end    
end

Dsorted




Rs


D_r = round(D*10000)/10000;
Dsorted = round(Dsorted*10000)/10000;
Rs_r = round(Rs*1000)/1000;

csvwrite('outputs/rs.csv',Rs_r);
csvwrite('outputs/d.csv',D_r);
csvwrite('outputs/dsorted.csv',Dsorted);


fLocal
fGlobal



